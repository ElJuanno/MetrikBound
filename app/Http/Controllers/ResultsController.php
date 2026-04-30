<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Response;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultsController extends Controller
{
    public function index()
    {
        $surveys = Survey::where('user_id', auth()->id())
            ->withCount('responses')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('results.index', compact('surveys'));
    }

    public function show(Survey $survey)
    {
        // Verificar que el usuario sea dueño de la encuesta
        abort_unless($survey->user_id === auth()->id(), 403);

        // Obtener todas las respuestas con usuario relacionado
        $responses = Response::where('survey_id', $survey->id)
            ->with(['answers', 'user'])
            ->orderBy('completed_at', 'desc')
            ->get();

        // Obtener el estado del builder para saber qué preguntas hay
        $builderState = is_array($survey->builder_state) 
            ? $survey->builder_state 
            : json_decode($survey->builder_state ?? '{}', true);

        $blocks = $builderState['blocks'] ?? [];

        // Crear un mapa de índices de preguntas
        $questionIndexMap = [];
        $questionIndex = 0;
        foreach ($blocks as $blockIndex => $block) {
            if (str_starts_with($block['kind'] ?? '', 'q_')) {
                $questionIndexMap[$blockIndex] = $questionIndex;
                $questionIndex++;
            }
        }

        // Filtrar solo bloques de preguntas
        $questions = collect($blocks)->filter(function($block) {
            return str_starts_with($block['kind'] ?? '', 'q_');
        })->values();

        // Procesar estadísticas por pregunta
        $stats = [];
        foreach ($blocks as $blockIndex => $block) {
            $kind = $block['kind'] ?? '';
            
            // Solo procesar bloques de preguntas
            if (!str_starts_with($kind, 'q_')) {
                continue;
            }

            $props = $block['props'] ?? [];
            $questionText = $props['html'] ?? $props['label'] ?? 'Pregunta';

            $stat = [
                'index' => $blockIndex,
                'kind' => $kind,
                'question' => $questionText,
                'total_responses' => 0,
                'data' => []
            ];

            // Recopilar respuestas para esta pregunta específica
            $questionAnswers = [];
            foreach ($responses as $response) {
                // Las respuestas se guardan en orden de envío del formulario
                // Necesitamos encontrar la respuesta que corresponde a este blockIndex
                // Los answers se guardan secuencialmente, necesitamos mapear por el orden de las preguntas
                
                $allAnswers = $response->answers->keyBy('id');
                
                // Buscar la respuesta que corresponde a este blockIndex
                // Como se guardan en el orden del foreach del formulario, necesitamos
                // encontrar cuál answer corresponde a este blockIndex
                
                // Obtener todos los blockIndexes de preguntas en orden
                $questionBlocks = [];
                foreach ($blocks as $idx => $b) {
                    if (str_starts_with($b['kind'] ?? '', 'q_')) {
                        $questionBlocks[] = $idx;
                    }
                }
                
                // Encontrar la posición de este blockIndex en la lista de preguntas
                $positionInQuestions = array_search($blockIndex, $questionBlocks);
                
                if ($positionInQuestions !== false && isset($response->answers[$positionInQuestions])) {
                    $answer = $response->answers[$positionInQuestions];
                    
                    if ($answer && $answer->value !== null && $answer->value !== '') {
                        $questionAnswers[] = [
                            'value' => $answer->value,
                            'response_id' => $response->id,
                            'date' => $response->completed_at
                        ];
                        $stat['total_responses']++;
                    }
                }
            }

            // Procesar según tipo de pregunta
            if (in_array($kind, ['q_radio', 'q_select', 'q_yesno'])) {
                // Preguntas de opción única
                $options = $props['options'] ?? [];
                
                // Para q_yesno, las opciones son fijas
                if ($kind === 'q_yesno') {
                    $options = ['Sí', 'No'];
                }
                
                $counts = [];

                foreach ($questionAnswers as $answer) {
                    $value = $answer['value'];
                    $counts[$value] = ($counts[$value] ?? 0) + 1;
                }

                // Calcular porcentajes
                foreach ($options as $option) {
                    $count = $counts[$option] ?? 0;
                    $percentage = $stat['total_responses'] > 0 
                        ? round(($count / $stat['total_responses']) * 100, 1) 
                        : 0;

                    $stat['data'][] = [
                        'label' => $option,
                        'count' => $count,
                        'percentage' => $percentage
                    ];
                }

            } elseif ($kind === 'q_check') {
                // Preguntas de checkbox (múltiple selección)
                $options = $props['options'] ?? [];
                $counts = [];

                foreach ($questionAnswers as $answer) {
                    $value = $answer['value'];
                    
                    // Si es un array JSON, decodificarlo
                    if (is_string($value) && (str_starts_with($value, '[') || str_starts_with($value, '{'))) {
                        $values = json_decode($value, true) ?? [];
                        if (is_array($values)) {
                            foreach ($values as $val) {
                                $counts[$val] = ($counts[$val] ?? 0) + 1;
                            }
                        }
                    } else {
                        $counts[$value] = ($counts[$value] ?? 0) + 1;
                    }
                }

                // Calcular porcentajes (basado en total de respuestas, no de selecciones)
                foreach ($options as $option) {
                    $count = $counts[$option] ?? 0;
                    $percentage = $stat['total_responses'] > 0 
                        ? round(($count / $stat['total_responses']) * 100, 1) 
                        : 0;

                    $stat['data'][] = [
                        'label' => $option,
                        'count' => $count,
                        'percentage' => $percentage
                    ];
                }

            } elseif (in_array($kind, ['q_scale', 'q_numeric', 'q_stars'])) {
                // Pregunta de escala, numérica o estrellas
                $min = (int)($props['min'] ?? 1);
                $max = (int)($props['max'] ?? 5);
                
                // Para q_stars, usar el número de estrellas configurado
                if ($kind === 'q_stars') {
                    $min = 1;
                    $max = (int)($props['stars'] ?? 5);
                } elseif ($kind === 'q_numeric') {
                    $min = (int)($props['min'] ?? 1);
                    $max = (int)($props['max'] ?? 10);
                }
                
                $counts = [];
                $sum = 0;

                foreach ($questionAnswers as $answer) {
                    $value = (int)$answer['value'];
                    $counts[$value] = ($counts[$value] ?? 0) + 1;
                    $sum += $value;
                }

                $average = $stat['total_responses'] > 0 
                    ? round($sum / $stat['total_responses'], 2) 
                    : 0;

                for ($i = $min; $i <= $max; $i++) {
                    $count = $counts[$i] ?? 0;
                    $percentage = $stat['total_responses'] > 0 
                        ? round(($count / $stat['total_responses']) * 100, 1) 
                        : 0;

                    $stat['data'][] = [
                        'label' => (string)$i,
                        'count' => $count,
                        'percentage' => $percentage
                    ];
                }

                $stat['average'] = $average;

            } else {
                // Preguntas abiertas (text, date)
                $stat['data'] = array_map(function($answer) {
                    return [
                        'response_id' => $answer['response_id'],
                        'value' => $answer['value'],
                        'date' => $answer['date']
                    ];
                }, $questionAnswers);
            }

            $stats[] = $stat;
        }

        return view('results.show', compact('survey', 'responses', 'stats', 'questions'));
    }
}
