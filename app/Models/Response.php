<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'survey_id',
        'user_id',
        'anonymous_token',
        'ip_hash',
        'user_agent',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function survey()
    {
        return $this->belongsTo(\App\Models\Survey::class);
    }

    public function answers()
    {
        return $this->hasMany(\App\Models\Answer::class);
    }
}