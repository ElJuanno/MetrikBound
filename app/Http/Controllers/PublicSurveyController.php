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
public function show(string $token)
{
    $survey = Survey::where('share_token', $token)
        ->where('status', 'published')
        ->firstOrFail();

    if ($survey->starts_at && now()->lt($survey->starts_at)) {
        abort(403, 'La encuesta aún no está disponible.');
    }

    if ($survey->ends_at && now()->gt($survey->ends_at)) {
        abort(403, 'La encuesta ya cerró.');
    }

    if ($survey->response_mode === 'registered' && !auth()->check()) {
        return redirect()->route('login');
    }

    $state = is_array($survey->builder_state)
        ? $survey->builder_state
        : json_decode($survey->builder_state ?? '{}', true);

    $nodes = $state['blocks'] ?? [];

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
        $survey = Survey::where('share_token', $token)
            ->where('status', 'published')
            ->firstOrFail();

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

        DB::transaction(function () use ($survey, $request, $answersInput) {
            $response = Response::create([
                'survey_id' => $survey->id,
                'user_id' => auth()->check() ? auth()->id() : null,
                'anonymous_token' => auth()->check() ? null : Str::random(40),
                'ip_hash' => hash('sha256', $request->ip()),
                'user_agent' => $request->userAgent(),
                'completed_at' => now(),
            ]);

            foreach ($answersInput as $blockIndex => $value) {
                if (is_array($value)) {
                    $value = json_encode($value, JSON_UNESCAPED_UNICODE);
                }

                Answer::create([
                    'response_id' => $response->id,
                    'question_id' => null,
                    'value' => $value,
                ]);
            }
        });

        return redirect()
            ->route('surveys.public.show', $survey->share_token)
            ->with('success', 'Gracias por responder la encuesta.');
    }
}