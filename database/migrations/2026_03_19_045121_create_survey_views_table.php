<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('survey_views', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('survey_id');
            $table->char('ip_hash', 64)->nullable();
            $table->timestamp('viewed_at')->useCurrent();

            $table->index(['survey_id', 'viewed_at'], 'survey_views_survey_time_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_views');
    }
};
