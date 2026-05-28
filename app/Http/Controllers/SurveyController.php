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
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:2000'],
            'is_public' => ['nullable'],
        ]);

        $survey = Survey::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'status' => 'draft',
            'response_mode' => 'anonymous',
            'allow_multiple_responses' => true,
            'is_public' => $request->has('is_public'),
            'visibility' => $request->has('is_public') ? 'public' : 'private',
            'share_token' => Str::uuid()->toString(),
            'builder_state' => ['v' => 2, 'page' => $this->defaultPage(), 'blocks' => []],
            'theme_json' => $this->defaultTheme(),
            'settings_json' => $this->defaultSettings(),
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

        $template = $this->templateCatalog()[$data['template_id']] ?? null;

        if (!$template) {
            return back()->withErrors(['template_id' => 'Plantilla no encontrada.']);
        }

        $survey = Survey::create([
            'user_id' => $request->user()->id,
            'title' => $data['title'],
            'description' => $template['description'],
            'status' => 'draft',
            'response_mode' => $template['response_mode'] ?? 'anonymous',
            'allow_multiple_responses' => true,
            'is_public' => true,
            'visibility' => 'public',
            'share_token' => Str::uuid()->toString(),
            'builder_state' => [
                'v' => 2,
                'page' => $this->defaultPage($template['background'] ?? '#ffffff'),
                'blocks' => $this->buildTemplateBlocks($template),
            ],
            'theme_json' => $this->defaultTheme($template['accent'] ?? '#0f766e'),
            'settings_json' => $this->defaultSettings(),
        ]);

        return redirect()
            ->route('builder.edit', $survey)
            ->with('success', 'Encuesta creada desde plantilla.');
    }

    public static function availableTemplates(): array
    {
        return (new self())->templateCatalog();
    }

    private function templateCatalog(): array
    {
        return [
            'customer_satisfaction' => [
                'title' => 'Satisfaccion del cliente',
                'description' => 'Mide experiencia, recomendacion y oportunidades de mejora despues de una compra o servicio.',
                'tag' => 'Clientes',
                'accent' => '#0f766e',
                'icon' => 'CS',
                'questions' => [
                    $this->radio('Como calificarias la calidad del producto o servicio?', ['Excelente', 'Muy buena', 'Buena', 'Regular', 'Mala']),
                    $this->yesNo('El producto o servicio cumplio con tus expectativas?'),
                    $this->stars('Como calificarias la atencion recibida?'),
                    $this->numeric('Del 1 al 10, que tan probable es que nos recomiendes?', 1, 10),
                    $this->radio('Que aspecto fue mas importante para tu experiencia?', ['Calidad', 'Precio', 'Tiempo de respuesta', 'Atencion', 'Facilidad de uso']),
                    $this->textQuestion('Que deberiamos mejorar?', 'Escribe tu sugerencia principal'),
                ],
            ],
            'event_registration' => [
                'title' => 'Registro de evento',
                'description' => 'Recopila datos de asistentes, preferencias y requerimientos para organizar un evento.',
                'tag' => 'Eventos',
                'accent' => '#0284c7',
                'icon' => 'EV',
                'questions' => [
                    $this->textQuestion('Nombre completo', 'Escribe tu nombre completo'),
                    $this->textQuestion('Correo electronico', 'tu@correo.com'),
                    $this->textQuestion('Telefono', '(55) 1234 5678'),
                    $this->select('Tipo de entrada', ['General', 'VIP', 'Invitado', 'Prensa']),
                    $this->radio('Como asistiras?', ['Presencial', 'En linea']),
                    $this->check('Que temas te interesan?', ['Networking', 'Talleres', 'Conferencias', 'Demostraciones']),
                    $this->textQuestion('Requerimientos especiales', 'Alergias, accesibilidad u otro comentario'),
                ],
            ],
            'product_feedback' => [
                'title' => 'Feedback de producto',
                'description' => 'Obtiene comentarios accionables sobre funciones, facilidad de uso y valor percibido.',
                'tag' => 'Producto',
                'accent' => '#7c3aed',
                'icon' => 'PF',
                'questions' => [
                    $this->numeric('Del 1 al 10, que tan util te resulta el producto?', 1, 10),
                    $this->radio('Que tan facil fue usarlo?', ['Muy facil', 'Facil', 'Neutral', 'Dificil', 'Muy dificil']),
                    $this->check('Que funciones usas con mas frecuencia?', ['Panel principal', 'Reportes', 'Plantillas', 'Colaboracion', 'Exportaciones']),
                    $this->textQuestion('Que problema intentabas resolver?', 'Describe el contexto de uso'),
                    $this->textQuestion('Que funcion agregarias o cambiarias?', 'Comparte tu idea'),
                    $this->yesNo('Recomendarias este producto a otra persona?'),
                ],
            ],
            'employee_evaluation' => [
                'title' => 'Evaluacion de empleados',
                'description' => 'Evalua desempeno, comunicacion, colaboracion y oportunidades de desarrollo.',
                'tag' => 'Equipo',
                'accent' => '#4f46e5',
                'icon' => 'HR',
                'response_mode' => 'registered',
                'questions' => [
                    $this->textQuestion('Nombre de la persona evaluada', 'Nombre completo'),
                    $this->select('Area o departamento', ['Ventas', 'Operacion', 'Producto', 'Administracion', 'Soporte']),
                    $this->numeric('Cumplimiento de objetivos', 1, 5),
                    $this->numeric('Comunicacion y seguimiento', 1, 5),
                    $this->numeric('Trabajo en equipo', 1, 5),
                    $this->textQuestion('Fortalezas principales', 'Describe comportamientos concretos'),
                    $this->textQuestion('Oportunidades de mejora', 'Describe acciones recomendadas'),
                ],
            ],
            'market_research' => [
                'title' => 'Encuesta de mercado',
                'description' => 'Explora preferencias, habitos de compra y sensibilidad de precio de una audiencia.',
                'tag' => 'Marketing',
                'accent' => '#d97706',
                'icon' => 'MR',
                'show_footer' => false,
                'questions' => [
                    $this->select('Rango de edad', ['18-24', '25-34', '35-44', '45-54', '55+']),
                    $this->radio('Con que frecuencia compras este tipo de producto?', ['Semanal', 'Mensual', 'Cada 3 meses', 'Rara vez', 'Nunca']),
                    $this->check('Que factores influyen en tu decision?', ['Precio', 'Calidad', 'Marca', 'Recomendaciones', 'Disponibilidad']),
                    $this->radio('Donde sueles comprar?', ['Tienda fisica', 'Sitio web', 'Marketplace', 'Redes sociales']),
                    $this->numeric('Del 1 al 10, que tan interesado estas en una nueva solucion?', 1, 10),
                    $this->textQuestion('Que necesidad no esta bien resuelta hoy?', 'Cuentanos tu experiencia'),
                ],
            ],
            'support_request' => [
                'title' => 'Contacto y soporte',
                'description' => 'Recibe solicitudes de ayuda con datos suficientes para priorizar y responder mejor.',
                'tag' => 'Soporte',
                'accent' => '#dc2626',
                'icon' => 'SP',
                'questions' => [
                    $this->textQuestion('Nombre', 'Tu nombre'),
                    $this->textQuestion('Correo de contacto', 'tu@correo.com'),
                    $this->select('Tipo de solicitud', ['Duda general', 'Problema tecnico', 'Facturacion', 'Sugerencia']),
                    $this->select('Prioridad', ['Baja', 'Media', 'Alta', 'Urgente']),
                    $this->textQuestion('Describe tu solicitud', 'Incluye el mayor contexto posible'),
                    $this->yesNo('Podemos contactarte para pedir mas informacion?'),
                ],
            ],
            'course_evaluation' => [
                'title' => 'Evaluacion de curso',
                'description' => 'Mide claridad, contenido, ritmo y utilidad de una clase, curso o capacitacion.',
                'tag' => 'Educacion',
                'accent' => '#0891b2',
                'icon' => 'ED',
                'show_footer' => false,
                'questions' => [
                    $this->stars('Califica la calidad general del curso'),
                    $this->numeric('Claridad del instructor', 1, 5),
                    $this->numeric('Utilidad del contenido', 1, 5),
                    $this->radio('El ritmo del curso fue...', ['Muy rapido', 'Adecuado', 'Muy lento']),
                    $this->check('Que recursos fueron mas utiles?', ['Videos', 'Ejercicios', 'Lecturas', 'Sesiones en vivo', 'Proyecto final']),
                    $this->textQuestion('Que tema te gustaria profundizar?', 'Escribe tu respuesta'),
                    $this->yesNo('Recomendarias este curso?'),
                ],
            ],
            'job_application' => [
                'title' => 'Solicitud de empleo',
                'description' => 'Captura informacion basica de candidatos para iniciar un proceso de seleccion.',
                'tag' => 'Reclutamiento',
                'accent' => '#2563eb',
                'icon' => 'JA',
                'response_mode' => 'registered',
                'questions' => [
                    $this->textQuestion('Nombre completo', 'Nombre y apellidos'),
                    $this->textQuestion('Correo electronico', 'tu@correo.com'),
                    $this->textQuestion('Telefono', 'Numero de contacto'),
                    $this->select('Area de interes', ['Ventas', 'Operacion', 'Producto', 'Marketing', 'Soporte']),
                    $this->radio('Disponibilidad', ['Inmediata', '2 semanas', '1 mes', 'Mas de 1 mes']),
                    $this->textQuestion('Experiencia relevante', 'Resume tu experiencia en pocas lineas'),
                    $this->textQuestion('Enlace a CV o portafolio', 'https://'),
                ],
            ],
            'appointment_booking' => [
                'title' => 'Reserva de cita',
                'description' => 'Permite solicitar citas con fecha, horario preferido y motivo de la visita.',
                'tag' => 'Servicios',
                'accent' => '#9333ea',
                'icon' => 'BK',
                'questions' => [
                    $this->textQuestion('Nombre completo', 'Tu nombre'),
                    $this->textQuestion('Correo o telefono', 'Dato de contacto'),
                    $this->date('Fecha preferida'),
                    $this->select('Horario preferido', ['Manana', 'Mediodia', 'Tarde', 'Noche']),
                    $this->select('Tipo de cita', ['Consulta inicial', 'Seguimiento', 'Soporte', 'Otro']),
                    $this->textQuestion('Motivo de la cita', 'Describe brevemente lo que necesitas'),
                    $this->yesNo('Aceptas recibir recordatorios?'),
                ],
            ],
            'post_purchase' => [
                'title' => 'Encuesta post-compra',
                'description' => 'Evalua la experiencia despues de una compra: entrega, producto y servicio.',
                'tag' => 'E-commerce',
                'accent' => '#16a34a',
                'icon' => 'PC',
                'questions' => [
                    $this->stars('Como calificas tu experiencia de compra?'),
                    $this->yesNo('Recibiste tu pedido en el tiempo esperado?'),
                    $this->radio('El producto llego en buen estado?', ['Si, perfecto', 'Con detalles menores', 'Con problemas importantes']),
                    $this->numeric('Del 1 al 10, que tan facil fue comprar?', 1, 10),
                    $this->check('Que mejorarias del proceso?', ['Informacion del producto', 'Pago', 'Envio', 'Seguimiento', 'Atencion']),
                    $this->textQuestion('Comentarios adicionales', 'Cuentanos cualquier detalle relevante'),
                ],
            ],
        ];
    }

    private function buildTemplateBlocks(array $template): array
    {
        $accent = $template['accent'] ?? '#0f766e';
        $blocks = [
            $this->block('header_band', 0, 0, 794, 118, [
                'html' => strtoupper($template['title']),
                'fontSize' => 22,
                'color' => '#ffffff',
                'bg' => $accent,
                'font' => 'poppins',
                'align' => 'center',
                'borderColor' => $accent,
                'borderWidth' => 0,
                'rotation' => 0,
            ], 1),
            $this->block('text', 642, 34, 92, 58, [
                'html' => $template['icon'] ?? 'MB',
                'fontSize' => 28,
                'color' => '#ffffff',
                'font' => 'poppins',
                'align' => 'center',
            ], 2),
            $this->block('text', 70, 138, 654, 54, [
                'html' => $template['description'],
                'fontSize' => 14,
                'color' => '#475569',
                'font' => 'system',
                'align' => 'center',
            ], 3),
            $this->block('divider', 70, 198, 654, null, [
                'dividerVariant' => 'gradient',
                'dividerColor' => $accent,
                'dividerThickness' => 2,
            ], 4),
        ];

        $y = 228;
        $z = 5;

        foreach ($template['questions'] as $question) {
            $blocks[] = $this->block(
                $question['kind'],
                70,
                $y,
                654,
                null,
                $this->questionProps($question),
                $z
            );

            $y += $this->questionSpacing($question['kind'], count($question['options'] ?? []));
            $z++;
        }

        if ($template['show_footer'] ?? true) {
            $blocks[] = $this->block('footer_band', 0, 1052, 794, 72, [
                'html' => null,
                'fontSize' => 14,
                'color' => '#ffffff',
                'bg' => $accent,
                'font' => 'system',
                'align' => 'center',
                'borderColor' => $accent,
                'borderWidth' => 0,
                'rotation' => 0,
            ], $z);
        }

        return $blocks;
    }

    private function block(string $kind, int $x, int $y, int $w, ?int $h, array $props, int $z): array
    {
        return [
            'id' => 'blk_' . Str::random(8),
            'kind' => $kind,
            'variant' => null,
            'x' => $x,
            'y' => $y,
            'w' => $w,
            'h' => $h,
            'locked' => false,
            'z' => $z,
            'props' => array_merge([
                'html' => null,
                'fontSize' => 14,
                'color' => '#0f172a',
                'bg' => '',
                'alpha' => 100,
                'font' => 'system',
                'align' => 'left',
                'optColor' => '#475569',
                'required' => false,
            ], $props),
        ];
    }

    private function questionProps(array $question): array
    {
        return [
            'html' => $question['label'],
            'fontSize' => 14,
            'color' => '#0f172a',
            'bg' => '',
            'alpha' => 100,
            'optColor' => '#475569',
            'placeholder' => $question['placeholder'] ?? 'Escribe tu respuesta',
            'options' => $question['options'] ?? [],
            'min' => $question['min'] ?? 1,
            'max' => $question['max'] ?? 5,
            'stars' => $question['stars'] ?? 5,
            'required' => false,
            'font' => 'system',
            'align' => 'left',
        ];
    }

    private function questionSpacing(string $kind, int $optionCount): int
    {
        return match ($kind) {
            'q_radio', 'q_check' => 78 + (max($optionCount, 2) * 24),
            'q_text' => 106,
            'q_stars', 'q_numeric', 'q_scale', 'q_yesno', 'q_select', 'q_date' => 100,
            default => 96,
        };
    }

    private function textQuestion(string $label, string $placeholder): array
    {
        return compact('label', 'placeholder') + ['kind' => 'q_text'];
    }

    private function radio(string $label, array $options): array
    {
        return compact('label', 'options') + ['kind' => 'q_radio'];
    }

    private function check(string $label, array $options): array
    {
        return compact('label', 'options') + ['kind' => 'q_check'];
    }

    private function select(string $label, array $options): array
    {
        return compact('label', 'options') + ['kind' => 'q_select'];
    }

    private function yesNo(string $label): array
    {
        return compact('label') + ['kind' => 'q_yesno'];
    }

    private function stars(string $label, int $stars = 5): array
    {
        return compact('label', 'stars') + ['kind' => 'q_stars'];
    }

    private function numeric(string $label, int $min, int $max): array
    {
        return compact('label', 'min', 'max') + ['kind' => 'q_numeric'];
    }

    private function date(string $label): array
    {
        return compact('label') + ['kind' => 'q_date'];
    }

    private function defaultTheme(string $primary = '#0f766e'): array
    {
        return [
            'primary' => $primary,
            'background' => '#F8FAFC',
            'text' => '#0F172A',
            'radius' => 12,
        ];
    }

    private function defaultSettings(): array
    {
        return [
            'anonymous' => true,
            'one_per_page' => true,
        ];
    }

    private function defaultPage(string $background = '#ffffff'): array
    {
        return [
            'bg' => [
                'type' => 'solid',
                'color' => $background,
            ],
        ];
    }
}
