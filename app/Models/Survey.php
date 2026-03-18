<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'response_mode',
        'allow_multiple_responses',
        'starts_at',
        'ends_at',
        'share_token',
        'builder_state',
        'settings_json',
        'theme_json',
        'visibility',
        'is_public',
    ];

    protected $casts = [
        'builder_state' => 'array',
        'settings_json' => 'array',
        'theme_json' => 'array',
        'allow_multiple_responses' => 'boolean',
        'is_public' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];

    public function questions()
    {
        return $this->hasMany(\App\Models\Question::class)->orderBy('position');
    }

    public function responses()
    {
        return $this->hasMany(\App\Models\Response::class);
    }
}