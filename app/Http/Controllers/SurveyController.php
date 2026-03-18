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
}
