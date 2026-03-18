<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\Response;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $surveysCount = Survey::where('user_id', $user->id)->count();

        $publishedCount = Survey::where('user_id', $user->id)
            ->where('status', 'published')
            ->count();

        $draftCount = Survey::where('user_id', $user->id)
            ->where('status', 'draft')
            ->count();

        $responsesCount = Response::whereHas('survey', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->count();

        $recentSurveys = Survey::withCount('responses')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'surveysCount',
            'publishedCount',
            'draftCount',
            'responsesCount',
            'recentSurveys'
        ));
    }
}