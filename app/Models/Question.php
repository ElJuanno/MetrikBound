<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'survey_id',
        'section_id',
        'block_kind',
        'type',
        'title',
        'description',
        'is_required',
        'position',
        'config_json',
        'validation_json',
    ];

    protected $casts = [
        'is_required' => 'boolean',
        'config_json' => 'array',
        'validation_json' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id')->orderBy('position');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    public function block()
    {
        return $this->hasOne(SurveyBlock::class, 'question_id');
    }
}