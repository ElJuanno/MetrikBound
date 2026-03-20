<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyBlock extends Model
{
    use HasFactory;

    protected $table = 'survey_blocks';

    protected $fillable = [
        'survey_id',
        'question_id',
        'type',
        'position',
        'x',
        'y',
        'width',
        'height',
        'props_json',
    ];

    protected $casts = [
        'props_json' => 'array',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}