<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'survey_id',
        'section_id',
        'type',
        'title',
        'description',
        'is_required',
        'position',
        'settings_json',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'settings_json' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function questionOptions()
    {
        return $this->hasMany(QuestionOption::class, 'question_id')->orderBy('position');
    }
}