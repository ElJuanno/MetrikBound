<?php

namespace Tests\Feature;

use App\Http\Controllers\SurveyController;
use App\Models\Survey;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TemplateCreationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_available_templates_create_builder_ready_surveys(): void
    {
        $user = User::factory()->create();

        foreach (SurveyController::availableTemplates() as $templateId => $template) {
            $response = $this
                ->actingAs($user)
                ->post(route('surveys.createFromTemplate'), [
                    'template_id' => $templateId,
                    'title' => $template['title'] . ' QA',
                ]);

            $survey = Survey::where('title', $template['title'] . ' QA')->firstOrFail();

            $response->assertRedirect(route('builder.edit', $survey));

            $this->assertSame($template['description'], $survey->description);
            $this->assertTrue($survey->is_public);
            $this->assertSame('draft', $survey->status);
            $this->assertNotEmpty($survey->share_token);

            $blocks = $survey->builder_state['blocks'] ?? [];
            $questionBlocks = collect($blocks)
                ->filter(fn (array $block) => str_starts_with($block['kind'] ?? '', 'q_'))
                ->values();

            $this->assertNotEmpty($blocks, "Template {$templateId} has no blocks.");
            $this->assertCount(count($template['questions']), $questionBlocks, "Template {$templateId} question count mismatch.");
            $this->assertSame(2, $survey->builder_state['v']);
            $this->assertArrayHasKey('page', $survey->builder_state);
        }
    }
}
