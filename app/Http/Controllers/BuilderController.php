<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;

class BuilderController extends Controller
{
    public function edit(Survey $survey)
    {
        return view('builder.edit', [
            'survey' => $survey,
            'builderState' => $survey->builder_state ?? null,
        ]);
    }

    public function autosave(Request $request, Survey $survey)
    {
        $data = $request->validate([
            'state' => ['required', 'array'],
        ]);

        $survey->builder_state = $data['state'];
        $survey->last_saved_at = now();
        $survey->save();

        return response()->json([
            'ok' => true,
            'saved_at' => $survey->last_saved_at->toDateTimeString(),
        ]);
    }
}
