<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Survey;
use Illuminate\Support\Str;

class GenerateSurveyTokens extends Command
{
    protected $signature = 'surveys:generate-tokens';
    protected $description = 'Genera tokens para encuestas que no tienen';

    public function handle()
    {
        $surveys = Survey::whereNull('share_token')
            ->orWhere('share_token', '')
            ->get();

        if ($surveys->isEmpty()) {
            $this->info('✅ Todas las encuestas ya tienen token.');
            return 0;
        }

        $count = 0;
        foreach ($surveys as $survey) {
            $survey->share_token = Str::uuid()->toString();
            $survey->save();
            $count++;
            $this->info("Token generado para encuesta #{$survey->id}: {$survey->title}");
        }

        $this->info("✅ Se generaron {$count} tokens.");
        return 0;
    }
}
