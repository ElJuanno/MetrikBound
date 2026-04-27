<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Response;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PublicSurveyController extends Controller
{
public function show(string $token, Request $request)
{
    $query = Survey::where('share_token', $token);
    
    $mode = $request->get('mode');
    
    // Si no está en modo preview/anonymous, solo mostrar publicadas
    if (!in_array($mode, ['anon', 'anonymous'], true)) {
        $query->where('status', 'published');
    }
    
    $survey = $query->firstOrFail();

    if ($survey->starts_at && now()->lt($survey->starts_at)) {
        abort(403, 'La encuesta aún no está disponible.');
    }

    if ($survey->ends_at && now()->gt($survey->ends_at)) {
        abort(403, 'La encuesta ya cerró.');
    }

    // Si el modo es 'registered' o la encuesta requiere registro, verificar autenticación
    if (($mode === 'registered' || $survey->response_mode === 'registered') && !auth()->check()) {
        // Guardar la URL completa para volver después del registro
        session()->put('intended_survey', $request->fullUrl());
        return redirect()->route('register')->with('message', 'Debes crear una cuenta para responder esta encuesta');
    }

    $state = is_array($survey->builder_state)
        ? $survey->builder_state
        : json_decode($survey->builder_state ?? '{}', true);

    $blocks = $state['blocks'] ?? [];

    // Mantener índices originales y agregar posición ordenada
    $nodes = [];
    foreach ($blocks as $originalIndex => $block) {
        $block['originalIndex'] = $originalIndex;
        $nodes[] = $block;
    }

    // Ordenar por posición visual pero mantener índice original
    usort($nodes, function ($a, $b) {
        $ay = $a['y'] ?? 0;
        $by = $b['y'] ?? 0;
        $ax = $a['x'] ?? 0;
        $bx = $b['x'] ?? 0;

        return [$ay, $ax] <=> [$by, $bx];
    });

    return view('surveys.public.show', compact('survey', 'state', 'nodes'));
}

    public function submit(Request $request, string $token)
    {
        $query = Survey::where('share_token', $token);
        
        // Si no está en modo preview/anonymous, solo permitir publicadas
        $mode = $request->get('mode');
        if (!in_array($mode, ['anon', 'anonymous'], true)) {
            $query->where('status', 'published');
        }
        
        $survey = $query->firstOrFail();

        if ($survey->starts_at && now()->lt($survey->starts_at)) {
            abort(403, 'La encuesta aún no está disponible.');
        }

        if ($survey->ends_at && now()->gt($survey->ends_at)) {
            abort(403, 'La encuesta ya cerró.');
        }

        if ($survey->response_mode === 'registered' && !auth()->check()) {
            return redirect()->route('login');
        }

        $answersInput = $request->input('answers', []);

        // Debug: verificar qué se está recibiendo
        \Log::info('Answers received:', ['answers' => $answersInput, 'all_input' => $request->all()]);

        if (!$survey->allow_multiple_responses) {
            if (auth()->check()) {
                $alreadyExists = Response::where('survey_id', $survey->id)
                    ->where('user_id', auth()->id())
                    ->exists();

                if ($alreadyExists) {
                    return back()->withErrors(['general' => 'Ya respondiste esta encuesta.']);
                }
            } else {
                $ipHash = hash('sha256', $request->ip());

                $alreadyExists = Response::where('survey_id', $survey->id)
                    ->where('ip_hash', $ipHash)
                    ->exists();

                if ($alreadyExists) {
                    return back()->withErrors(['general' => 'Ya respondiste esta encuesta.']);
                }
            }
        }

        // Obtener el builder state para saber el orden de los bloques
        $builderState = is_array($survey->builder_state) 
            ? $survey->builder_state 
            : json_decode($survey->builder_state ?? '{}', true);
        
        $blocks = $builderState['blocks'] ?? [];

        DB::transaction(function () use ($survey, $request, $answersInput, $blocks) {
            \Log::info('Starting transaction', ['answersInput' => $answersInput]);
            
            $response = Response::create([
                'survey_id' => $survey->id,
                'user_id' => auth()->check() ? auth()->id() : null,
                'anonymous_token' => auth()->check() ? null : Str::random(40),
                'ip_hash' => hash('sha256', $request->ip()),
                'user_agent' => $request->userAgent(),
                'completed_at' => now(),
            ]);

            \Log::info('Response created', ['response_id' => $response->id]);

            // Guardar respuestas en el orden de los bloques (no en el orden del formulario)
            foreach ($blocks as $blockIndex => $block) {
                // Solo procesar bloques de preguntas
                if (!str_starts_with($block['kind'] ?? '', 'q_')) {
                    continue;
                }
                
                // Buscar si hay respuesta para este bloque
                if (!isset($answersInput[$blockIndex])) {
                    continue;
                }
                
                $value = $answersInput[$blockIndex];
                
                \Log::info('Saving answer:', ['blockIndex' => $blockIndex, 'value' => $value, 'type' => gettype($value)]);
                
                if (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                }

                $answer = Answer::create([
                    'response_id' => $response->id,
                    'question_id' => null,
                    'value' => $value,
                ]);
                
                \Log::info('Answer created', ['answer_id' => $answer->id, 'saved_value' => $answer->value]);
            }
            
            \Log::info('Transaction completed');
        });

        $redirectUrl = route('surveys.public.show', $survey->share_token);
        
        // Mantener el parámetro mode si existe
        if ($request->has('mode')) {
            $redirectUrl .= '?mode=' . $request->get('mode');
        }

        return redirect($redirectUrl)
            ->with('success', 'Gracias por responder la encuesta.');
    }
}