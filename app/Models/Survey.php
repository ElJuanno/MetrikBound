<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Survey extends Model
{
    use HasFactory;

    protected $table = 'surveys';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'response_mode',
        'allow_multiple_responses',
        'is_public',
        'visibility',
        'share_token',
        'theme_json',
        'builder_state',
        'settings_json',
        'last_saved_at',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'allow_multiple_responses' => 'boolean',
        'theme_json' => 'array',
        'builder_state' => 'array',
        'settings_json' => 'array',
        'last_saved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($survey) {
            if (empty($survey->share_token)) {
                $survey->share_token = Str::uuid()->toString();
            }
        });
    }

    public function regenerateShareToken()
    {
        $this->share_token = Str::uuid()->toString();
        $this->save();
        return $this->share_token;
    }

    public function getPublicUrl()
    {
        return route('surveys.public.show', $this->share_token);
    }

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
