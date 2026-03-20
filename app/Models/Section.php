<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $table = 'sections';

    protected $fillable = [
        'survey_id',
        'title',
        'description',
        'position',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'section_id')->orderBy('position');
    }
}