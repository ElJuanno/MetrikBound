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
        Schema::create('responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('survey_id')->index('idx_responses_survey_id');
            $table->unsignedBigInteger('user_id')->nullable()->index('idx_responses_user_id');
            $table->string('anonymous_token', 64)->nullable();
            $table->char('ip_hash', 64)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
            $table->timestamp('completed_at')->nullable()->index('idx_responses_completed_at');

            $table->index(['survey_id', 'created_at'], 'responses_survey_time_index');
            $table->index(['user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
