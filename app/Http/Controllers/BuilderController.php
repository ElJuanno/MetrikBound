<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\Question;
use App\Models\QuestionOption;

class BuilderController extends Controller
{
public function edit(Survey $survey)
{
    $survey->load([
        'blocks.question.options',
    ]);

    $blocks = $survey->blocks
        ->sortBy('position')
        ->map(function ($block) {
            $props = $block->props_json ?? [];

            $kind = $this->mapDbTypeToBuilderKind(
                $block->type,
                $block->question?->type
            );

            $html = $props['text']
                ?? $props['label']
                ?? $block->question?->title
                ?? 'Bloque';

            $options = $props['options']
                ?? ($block->question
                    ? $block->question->options->pluck('label')->values()->all()
                    : null);

            return [
                'id' => 'db_' . $block->id,
                'dbId' => $block->id,
                'questionId' => $block->question_id,
                'optionIds' => $block->question
                    ? $block->question->options->pluck('id')->values()->all()
                    : [],
                'kind' => $kind,
                'variant' => null,
                'x' => (int) ($block->x ?? 80),
                'y' => (int) ($block->y ?? 120),
                'w' => (int) ($block->width ?? 360),
                'h' => $block->height ? (int) $block->height : null,
                'locked' => false,
                'z' => (int) ($block->position ?? 1),
                'props' => [
                    'html' => $html,
                    'align' => $props['textAlign'] ?? 'left',
                    'required' => (bool) ($props['required'] ?? $block->question?->is_required ?? false),
                    'options' => $options,
                    'font' => $props['font'] ?? 'system',
                    'fontSize' => (int) ($props['fontSize'] ?? 14),
                    'color' => $props['color'] ?? '',
                    'bg' => $props['backgroundColor'] ?? '',
                    'alpha' => (int) ($props['alpha'] ?? 100),
                    'optColor' => $props['optionColor'] ?? '',
                    'img' => $props['src'] ?? null,
                ],
            ];
        })
        ->values()
        ->all();

    $builderState = [
        'v' => 2,
        'page' => null,
        'blocks' => $blocks,
    ];

    return view('builder.edit', compact('survey', 'builderState'));
}
private function mapDbTypeToBuilderKind(?string $blockType, ?string $questionType = null): string
{
    return match ($questionType ?: $blockType) {
        'title' => 'title',
        'paragraph' => 'text',
        'divider' => 'divider',
        'image' => 'img',
        'text' => 'q_text',
        'radio' => 'q_radio',
        'checkbox' => 'q_check',
        'select' => 'q_select',
        'scale' => 'q_scale',
        'date' => 'q_date',
        default => 'text',
    };
}
public function updateBlock(Request $request, Survey $survey, SurveyBlock $block)
{
    if ((int) $block->survey_id !== (int) $survey->id) {
        abort(404);
    }

    $data = $request->validate([
        'type' => ['required', 'string'],
        'x' => ['required', 'integer'],
        'y' => ['required', 'integer'],
        'width' => ['nullable', 'integer'],
        'height' => ['nullable', 'integer'],
        'content' => ['nullable', 'string'],
        'required' => ['nullable', 'boolean'],
        'options' => ['nullable', 'array'],
        'options.*' => ['nullable', 'string'],
        'props_json' => ['nullable', 'array'],
    ]);

    return DB::transaction(function () use ($data, $block) {
        $mapped = $this->mapBuilderType($data['type']);

        $block->update([
            'type' => $mapped['block_type'],
            'x' => $data['x'],
            'y' => $data['y'],
            'width' => $data['width'] ?? 360,
            'height' => $data['height'] ?? null,
            'props_json' => $data['props_json'] ?? $this->defaultBlockProps($data['type'], $data),
        ]);

        if ($block->question && $mapped['block_kind'] === 'question') {
            $block->question->update([
                'type' => $mapped['question_type'],
                'title' => !empty($data['content']) ? $data['content'] : $this->defaultQuestionLabel($data['type']),
                'is_required' => (bool) ($data['required'] ?? false),
                'position' => $block->position,
                'config_json' => $this->defaultQuestionConfig($data['type'], $data),
                'validation_json' => [],
            ]);

            if (in_array($data['type'], ['q_radio', 'q_check', 'q_select'], true)) {
                $block->question->options()->delete();

                $options = $data['options'] ?? $this->defaultOptionsForType($data['type']);

                foreach ($options as $index => $optionLabel) {
                    $optionLabel = trim((string) $optionLabel);

                    if ($optionLabel === '') {
                        continue;
                    }

                    QuestionOption::create([
                        'question_id' => $block->question->id,
                        'label' => $optionLabel,
                        'value' => $optionLabel,
                        'position' => $index + 1,
                    ]);
                }
            }
        }

        return response()->json([
            'ok' => true,
            'message' => 'Bloque actualizado correctamente.',
        ]);
    });
}

public function deleteBlock(Survey $survey, SurveyBlock $block)
{
    if ((int) $block->survey_id !== (int) $survey->id) {
        abort(404);
    }

    DB::transaction(function () use ($block) {
        if ($block->question) {
            $block->question->options()->delete();
            $block->question->delete();
        }

        $block->delete();
    });

    return response()->json([
        'ok' => true,
        'message' => 'Bloque eliminado correctamente.',
    ]);
}
    public function autosave(Request $request, Survey $survey)
    {
        $data = $request->validate([
            'builder_state' => ['nullable', 'array'],
        ]);

        $survey->builder_state = $data['builder_state'] ?? [];
        $survey->save();

        return response()->json([
            'ok' => true,
            'message' => 'Guardado automático correcto.',
        ]);
    }

    public function storeBlock(Request $request, Survey $survey)
    {
        $data = $request->validate([
            'type' => ['required', 'string'],
            'x' => ['required', 'integer'],
            'y' => ['required', 'integer'],
            'width' => ['nullable', 'integer'],
            'height' => ['nullable', 'integer'],
            'content' => ['nullable', 'string'],
            'required' => ['nullable', 'boolean'],
            'options' => ['nullable', 'array'],
            'options.*' => ['nullable', 'string'],
        ]);

        return DB::transaction(function () use ($data, $survey) {
            $mapped = $this->mapBuilderType($data['type']);

            $nextPosition = (int) SurveyBlock::where('survey_id', $survey->id)->max('position') + 1;

            $block = SurveyBlock::create([
                'survey_id' => $survey->id,
                'question_id' => null,
                'type' => $mapped['block_type'],
                'position' => $nextPosition,
                'x' => $data['x'],
                'y' => $data['y'],
                'width' => $data['width'] ?? 320,
                'height' => $data['height'] ?? 120,
                'props_json' => $this->defaultBlockProps($data['type'], $data),
            ]);

            $question = null;
            $createdOptions = [];

            if ($mapped['block_kind'] === 'question') {
                $question = Question::create([
                    'survey_id' => $survey->id,
                    'section_id' => null,
                    'block_kind' => 'question',
                    'type' => $mapped['question_type'],
                    'title' => !empty($data['content']) ? $data['content'] : $this->defaultQuestionLabel($data['type']),
                    'description' => null,
                    'is_required' => (bool) ($data['required'] ?? false),
                    'position' => $nextPosition,
                    'config_json' => $this->defaultQuestionConfig($data['type'], $data),
                    'validation_json' => [],
                ]);

                $block->question_id = $question->id;
                $block->save();

                $options = $data['options'] ?? $this->defaultOptionsForType($data['type']);

                foreach ($options as $index => $optionLabel) {
                    $optionLabel = trim((string) $optionLabel);

                    if ($optionLabel === '') {
                        continue;
                    }

                    $createdOptions[] = QuestionOption::create([
                        'question_id' => $question->id,
                        'label' => $optionLabel,
                        'value' => $optionLabel,
                        'position' => $index + 1,
                    ]);
                }
            }

            return response()->json([
                'ok' => true,
                'message' => 'Bloque creado correctamente.',
                'block' => $block->fresh(),
                'question' => $question?->fresh(),
                'options' => $createdOptions,
            ]);
        });
    }

    private function mapBuilderType(string $builderType): array
    {
        return match ($builderType) {
            'title' => [
                'block_kind' => 'content',
                'block_type' => 'title',
                'question_type' => null,
            ],
            'text' => [
                'block_kind' => 'content',
                'block_type' => 'paragraph',
                'question_type' => null,
            ],
            'divider' => [
                'block_kind' => 'content',
                'block_type' => 'divider',
                'question_type' => null,
            ],
            'img' => [
                'block_kind' => 'content',
                'block_type' => 'image',
                'question_type' => null,
            ],
            'q_text' => [
                'block_kind' => 'question',
                'block_type' => 'text',
                'question_type' => 'text',
            ],
            'q_radio' => [
                'block_kind' => 'question',
                'block_type' => 'radio',
                'question_type' => 'radio',
            ],
            'q_check' => [
                'block_kind' => 'question',
                'block_type' => 'checkbox',
                'question_type' => 'checkbox',
            ],
            'q_select' => [
                'block_kind' => 'question',
                'block_type' => 'select',
                'question_type' => 'select',
            ],
            'q_scale' => [
                'block_kind' => 'question',
                'block_type' => 'scale',
                'question_type' => 'scale',
            ],
            'q_date' => [
                'block_kind' => 'question',
                'block_type' => 'date',
                'question_type' => 'date',
            ],
            default => [
                'block_kind' => 'content',
                'block_type' => 'paragraph',
                'question_type' => null,
            ],
        };
    }

    private function defaultBlockProps(string $builderType, array $data): array
    {
        return match ($builderType) {
            'title' => [
                'text' => $data['content'] ?? 'Nuevo título',
                'fontSize' => 28,
                'textAlign' => 'left',
                'color' => '#0f172a',
                'backgroundColor' => '#ffffff',
                'alpha' => 100,
            ],
            'text' => [
                'text' => $data['content'] ?? 'Escribe un párrafo aquí',
                'fontSize' => 14,
                'textAlign' => 'left',
                'color' => '#334155',
                'backgroundColor' => '#ffffff',
                'alpha' => 100,
            ],
            'divider' => [
                'style' => 'solid',
                'color' => '#cbd5e1',
                'alpha' => 100,
            ],
            'img' => [
                'src' => null,
                'alt' => 'Imagen',
                'backgroundColor' => '#ffffff',
                'alpha' => 100,
            ],
            'q_text' => [
                'label' => $data['content'] ?? 'Nueva pregunta',
                'placeholder' => 'Escribe tu respuesta',
                'textAlign' => 'left',
                'color' => '#0f172a',
                'optionColor' => '#0f172a',
                'backgroundColor' => '#ffffff',
                'alpha' => 100,
                'required' => (bool) ($data['required'] ?? false),
            ],
            'q_radio', 'q_check', 'q_select' => [
                'label' => $data['content'] ?? 'Nueva pregunta',
                'options' => $data['options'] ?? $this->defaultOptionsForType($builderType),
                'textAlign' => 'left',
                'color' => '#0f172a',
                'optionColor' => '#0f172a',
                'backgroundColor' => '#ffffff',
                'alpha' => 100,
                'required' => (bool) ($data['required'] ?? false),
            ],
            'q_scale' => [
                'label' => $data['content'] ?? 'Califica del 1 al 5',
                'min' => 1,
                'max' => 5,
                'textAlign' => 'left',
                'color' => '#0f172a',
                'backgroundColor' => '#ffffff',
                'alpha' => 100,
                'required' => (bool) ($data['required'] ?? false),
            ],
            'q_date' => [
                'label' => $data['content'] ?? 'Selecciona una fecha',
                'textAlign' => 'left',
                'color' => '#0f172a',
                'backgroundColor' => '#ffffff',
                'alpha' => 100,
                'required' => (bool) ($data['required'] ?? false),
            ],
            default => [
                'text' => $data['content'] ?? '',
            ],
        };
    }

    private function defaultQuestionConfig(string $builderType, array $data): array
    {
        return match ($builderType) {
            'q_text' => [
                'placeholder' => 'Escribe tu respuesta',
            ],
            'q_radio', 'q_check', 'q_select' => [
                'options' => $data['options'] ?? $this->defaultOptionsForType($builderType),
            ],
            'q_scale' => [
                'min' => 1,
                'max' => 5,
                'step' => 1,
            ],
            'q_date' => [
                'format' => 'Y-m-d',
            ],
            default => [],
        };
    }

    private function defaultQuestionLabel(string $builderType): string
    {
        return match ($builderType) {
            'q_text' => 'Nueva pregunta de texto',
            'q_radio' => 'Nueva pregunta de opción múltiple',
            'q_check' => 'Nueva pregunta de checkbox',
            'q_select' => 'Nueva pregunta de selección',
            'q_scale' => 'Nueva pregunta de escala',
            'q_date' => 'Nueva pregunta de fecha',
            default => 'Nuevo bloque',
        };
    }

    private function defaultOptionsForType(string $builderType): array
    {
        return match ($builderType) {
            'q_radio', 'q_check', 'q_select' => [
                'Opción 1',
                'Opción 2',
                'Opción 3',
            ],
            default => [],
        };
    }
}