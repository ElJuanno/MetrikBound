<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'visibility',
        'share_token',
        'builder_state',
        'settings_json',
    ];

    protected $casts = [
        'builder_state' => 'array',
        'settings_json' => 'array',
    ];

    public function blocks()
    {
        return $this->hasMany(SurveyBlock::class, 'survey_id')->orderBy('position');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'survey_id')->orderBy('position');
    }

    public function sections()
    {
        return $this->hasMany(Section::class, 'survey_id')->orderBy('position');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'survey_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}