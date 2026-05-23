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
        'event_registration' => [
            // Ícono decorativo
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'text',
                'variant' => null,
                'x' => 350,
                'y' => 40,
                'w' => 94,
                'h' => 80,
                'locked' => false,
                'z' => 1,
                'props' => [
                    'html' => '📅',
                    'fontSize' => 48,
                    'color' => '#3b82f6',
                    'bg' => '',
                    'alpha' => 100,
                    'font' => 'system',
                    'align' => 'center',
                ],
            ],
            
            // Título principal
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'title',
                'variant' => null,
                'x' => 100,
                'y' => 130,
                'w' => 594,
                'h' => 60,
                'locked' => false,
                'z' => 2,
                'props' => [
                    'html' => 'Registro de Evento',
                    'fontSize' => 32,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'font' => 'system',
                    'align' => 'center',
                ],
            ],
            
            // Subtítulo
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'text',
                'variant' => null,
                'x' => 150,
                'y' => 195,
                'w' => 494,
                'h' => 40,
                'locked' => false,
                'z' => 3,
                'props' => [
                    'html' => 'Completa tus datos para asegurar tu lugar',
                    'fontSize' => 14,
                    'color' => '#64748b',
                    'bg' => '',
                    'alpha' => 100,
                    'font' => 'system',
                    'align' => 'center',
                ],
            ],
            
            // Divider
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'divider',
                'variant' => 'simple',
                'x' => 350,
                'y' => 240,
                'w' => 94,
                'h' => null,
                'locked' => false,
                'z' => 4,
                'props' => [
                    'dividerVariant' => 'simple',
                    'dividerColor' => '#3b82f6',
                    'dividerThickness' => 3,
                ],
            ],
            
            // Nombre completo
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_text',
                'variant' => null,
                'x' => 50,
                'y' => 270,
                'w' => 340,
                'h' => null,
                'locked' => false,
                'z' => 5,
                'props' => [
                    'html' => 'Nombre completo',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'placeholder' => 'Escribe tu nombre completo',
                    'required' => true,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Correo electrónico
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_text',
                'variant' => null,
                'x' => 404,
                'y' => 270,
                'w' => 340,
                'h' => null,
                'locked' => false,
                'z' => 6,
                'props' => [
                    'html' => 'Correo electrónico',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'placeholder' => 'ejemplo@correo.com',
                    'required' => true,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Teléfono
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_text',
                'variant' => null,
                'x' => 50,
                'y' => 380,
                'w' => 340,
                'h' => null,
                'locked' => false,
                'z' => 7,
                'props' => [
                    'html' => 'Teléfono',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'placeholder' => '(55) 1234 5678',
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Nombre del evento
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_text',
                'variant' => null,
                'x' => 404,
                'y' => 380,
                'w' => 340,
                'h' => null,
                'locked' => false,
                'z' => 8,
                'props' => [
                    'html' => 'Nombre del evento',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'placeholder' => 'Escribe el nombre del evento',
                    'required' => true,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Fecha del evento
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_date',
                'variant' => null,
                'x' => 50,
                'y' => 490,
                'w' => 340,
                'h' => null,
                'locked' => false,
                'z' => 9,
                'props' => [
                    'html' => 'Fecha del evento',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'required' => true,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Hora (como texto)
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_text',
                'variant' => null,
                'x' => 404,
                'y' => 490,
                'w' => 340,
                'h' => null,
                'locked' => false,
                'z' => 10,
                'props' => [
                    'html' => 'Hora',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'placeholder' => 'Selecciona la hora',
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Número de asistentes
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_numeric',
                'variant' => null,
                'x' => 50,
                'y' => 600,
                'w' => 340,
                'h' => null,
                'locked' => false,
                'z' => 11,
                'props' => [
                    'html' => 'Número de asistentes',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'min' => 1,
                    'max' => 10,
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Tipo de boleto
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_select',
                'variant' => null,
                'x' => 404,
                'y' => 600,
                'w' => 340,
                'h' => null,
                'locked' => false,
                'z' => 12,
                'props' => [
                    'html' => 'Tipo de boleto',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'options' => ['General', 'VIP'],
                    'required' => true,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Comentarios
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_text',
                'variant' => null,
                'x' => 50,
                'y' => 710,
                'w' => 694,
                'h' => null,
                'locked' => false,
                'z' => 13,
                'props' => [
                    'html' => 'Comentarios o requerimientos especiales',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'placeholder' => 'Cuéntanos algo relevante que debamos saber...',
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Acepto términos
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'q_check',
                'variant' => null,
                'x' => 50,
                'y' => 820,
                'w' => 694,
                'h' => null,
                'locked' => false,
                'z' => 14,
                'props' => [
                    'html' => 'Confirmación',
                    'fontSize' => 14,
                    'color' => '#1e293b',
                    'bg' => '',
                    'alpha' => 100,
                    'optColor' => '#475569',
                    'options' => ['Acepto los términos y condiciones', 'Deseo recibir notificaciones del evento'],
                    'required' => false,
                    'font' => 'system',
                    'align' => 'left',
                ],
            ],
            
            // Footer band
            [
                'id' => 'blk_' . Str::random(8),
                'kind' => 'footer_band',
                'variant' => null,
                'x' => 0,
                'y' => 1050,
                'w' => 794,
                'h' => 73,
                'locked' => false,
                'z' => 15,
                'props' => [
                    'html' => null,
                    'fontSize' => 14,
                    'color' => '#ffffff',
                    'bg' => '#3b82f6',
                    'alpha' => 100,
                    'font' => 'system',
                    'align' => 'center',
                    'borderColor' => '#3b82f6',
                    'borderWidth' => 0,
                    'rotation' => 0,
                ],
            ],
        ],
        
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
                'w' => 220,
                'h' => 210,
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
