<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $surveys = Survey::query()
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(10);

        return view('surveys.index', compact('surveys'));
    }

    public function create()
    {
        
        return view('surveys.create');
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'title' => ['required','string','max:200'],
        'description' => ['nullable','string','max:2000'],
        'is_public' => ['nullable'],
    ]);

    $survey = Survey::create([
        'user_id' => $request->user()->id,
        'title' => $data['title'],
        'description' => $data['description'] ?? null,
        'status' => 'draft',
        'is_public' => $request->has('is_public'),
        'share_token' => Str::uuid()->toString(),
        'theme_json' => [
            'primary' => '#1A73E8',
            'background' => '#F8F9FA',
            'text' => '#202124',
            'radius' => 16,
        ],
        'settings_json' => [
            'anonymous' => true,
            'one_per_page' => true,
        ],
    ]);

    if (!\Route::has('builder.edit')) {
        return redirect()->route('surveys.index')->with('ok', 'Encuesta creada.');
    }

    return redirect()->route('builder.edit', $survey);
}
public function publish(Survey $survey)
{
    abort_unless($survey->user_id === auth()->id(), 403);

    if (empty($survey->share_token)) {
        $survey->share_token = (string) Str::uuid();
    }

    $survey->status = 'published';
    $survey->save();

    return back()->with('success', 'Encuesta publicada correctamente.');
}

public function destroy(Survey $survey)
{
    abort_unless($survey->user_id === auth()->id(), 403);

    $survey->delete();

    return redirect()->route('surveys.index')->with('success', 'Encuesta eliminada correctamente.');
}

public function createFromTemplate(Request $request)
{
    $data = $request->validate([
        'title' => ['required', 'string', 'max:200'],
        'template_id' => ['required', 'string'],
    ]);

    $templateBlocks = $this->getTemplateBlocks($data['template_id']);
    
    if (!$templateBlocks) {
        return back()->withErrors(['template_id' => 'Plantilla no encontrada']);
    }

    $survey = Survey::create([
        'user_id' => $request->user()->id,
        'title' => $data['title'],
        'description' => null,
        'status' => 'draft',
        'is_public' => false,
        'share_token' => Str::uuid()->toString(),
        'builder_state' => [
            'version' => 2,
            'blocks' => $templateBlocks,
        ],
        'theme_json' => [
            'primary' => '#3f73c9',
            'background' => '#F8F9FA',
            'text' => '#202124',
            'radius' => 16,
        ],
        'settings_json' => [
            'anonymous' => true,
            'one_per_page' => true,
        ],
    ]);

    return redirect()->route('builder.edit', $survey)->with('success', 'Encuesta creada desde plantilla');
}

private function getTemplateBlocks($templateId)
{
    $templates = [
        'customer_satisfaction' => [
            // Header band - banda azul superior
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'header_band',
                'variant' => null,
                'x' => 0,
                'y' => 0,
                'w' => 590,
                'h' => 124,
                'locked' => false,
                'z' => 1,
                'props' => [
                    'html' => 'ENCUESTA DE SATISFACCIÓN DEL CLIENTE',
                    'fontSize' => 24,
                    'color' => '#ffffff',
                    'bg' => '#3f73c9',
                    'alpha' => 100,
                    'font' => 'poppins',
                    'align' => 'center',
                    'borderColor' => '#3f73c9',
                    'borderWidth' => 0,
                    'rotation' => 0,
                ],
            ],
            
            // Logo - esquina superior derecha
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'img',
                'variant' => null,
                'x' => 580,
                'y' => 10,
                'w' => 110,
                'h' => 60,
                'locked' => false,
                'z' => 2,
                'props' => [
                    'html' => null,
                    'fontSize' => 14,
                    'color' => '',
                    'bg' => '',
                    'alpha' => 100,
                    'font' => 'system',
                    'align' => 'left',
                    'optColor' => '',
                    'img' => '/images/Logos.png',
                    'required' => false,
                    'options' => null,
                ],
            ],
            
            // Subtítulo descriptivo
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'text',
                'variant' => null,
                'x' => 0,
                'y' => 125,
                'w' => 591,
                'h' => 71,
                'locked' => false,
                'z' => 3,
                'props' => [
                    'html' => 'Apreciamos tu opinión. Por favor, tómate un momento para responder las siguientes preguntas sobre tu experiencia con nuestros productos y servicios.',
                    'fontSize' => 13,
                    'color' => '#64748b',
                    'bg' => '',
                    'alpha' => 100,
                    'font' => 'system',
                    'align' => 'center',
                ],
            ],
            
            // Divider decorativo
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'divider',
                'variant' => 'gradient',
                'x' => 60,
                'y' => 190,
                'w' => 674,
                'h' => null,
                'locked' => false,
                'z' => 4,
                'props' => [
                    'dividerVariant' => 'gradient',
                    'dividerColor' => '#3f73c9',
                    'dividerThickness' => 2,
                ],
            ],
            
            // Pregunta 1 - Radio
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_radio',
                'variant' => null,
                'x' => 60,
                'y' => 230,
                'w' => 674,
                'h' => null,
                'locked' => false,
                'z' => 5,
                'props' => [
                    'html' => '1. ¿Cómo calificarías la calidad de nuestro producto/servicio?',
                    'fontSize' => 14,
                    'color' => '#0f172a',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'options' => ['Excelente', 'Muy buena', 'Buena', 'Regular'],
                    'visualStyle' => 'modern',
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Pregunta 2 - Yes/No
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_yesno',
                'variant' => null,
                'x' => 60,
                'y' => 400,
                'w' => 674,
                'h' => null,
                'locked' => false,
                'z' => 6,
                'props' => [
                    'html' => '2. ¿El producto/servicio cumplió con tus expectativas?',
                    'fontSize' => 14,
                    'color' => '#0f172a',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'visualStyle' => 'modern',
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Pregunta 3 - Stars
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_stars',
                'variant' => null,
                'x' => 60,
                'y' => 510,
                'w' => 674,
                'h' => null,
                'locked' => false,
                'z' => 7,
                'props' => [
                    'html' => '3. ¿Cómo calificarías la atención al cliente recibida?',
                    'fontSize' => 14,
                    'color' => '#0f172a',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'stars' => 5,
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Pregunta 4 - Scale
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_numeric',
                'variant' => null,
                'x' => 60,
                'y' => 640,
                'w' => 674,
                'h' => null,
                'locked' => false,
                'z' => 8,
                'props' => [
                    'html' => '4. Del 1 al 5, ¿qué tan probable es que recomiendes nuestros servicios?',
                    'fontSize' => 14,
                    'color' => '#0f172a',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'min' => 1,
                    'max' => 5,
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Pregunta 5 - Yes/No
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_yesno',
                'variant' => null,
                'x' => 60,
                'y' => 770,
                'w' => 674,
                'h' => null,
                'locked' => false,
                'z' => 9,
                'props' => [
                    'html' => '5. ¿Volverías a comprar nuestros productos/servicios?',
                    'fontSize' => 14,
                    'color' => '#0f172a',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'visualStyle' => 'modern',
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Pregunta 6 - Texto abierto
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_text',
                'variant' => null,
                'x' => 60,
                'y' => 900,
                'w' => 674,
                'h' => null,
                'locked' => false,
                'z' => 10,
                'props' => [
                    'html' => '6. ¿Tienes algún comentario adicional o sugerencia de mejora?',
                    'fontSize' => 14,
                    'color' => '#0f172a',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'visualStyle' => 'modern',
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Footer band - banda azul inferior
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'footer_band',
                'variant' => null,
                'x' => 0,
                'y' => 1050,
                'w' => 794,
                'h' => 80,
                'locked' => false,
                'z' => 11,
                'props' => [
                    'html' => null,
                    'fontSize' => 14,
                    'color' => '#ffffff',
                    'bg' => '#3f73c9',
                    'alpha' => 100,
                    'font' => 'system',
                    'align' => 'center',
                    'borderColor' => '#3f73c9',
                    'borderWidth' => 0,
                    'rotation' => 0,
                ],
            ],
        ],
    ];

    return $templates[$templateId] ?? null;
}
}
