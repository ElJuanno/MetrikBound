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
        Schema::create('questions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('survey_id')->index('idx_questions_survey_id');
            $table->unsignedBigInteger('section_id')->nullable()->index('idx_questions_section_id');
            $table->enum('block_kind', ['question', 'content'])->default('question')->index('idx_questions_block_kind');
            $table->enum('type', ['title', 'paragraph', 'divider', 'text', 'textarea', 'email', 'number', 'phone', 'date', 'select', 'radio', 'checkbox', 'scale', 'rating']);
            $table->text('title');
            $table->text('description')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('position')->index('idx_questions_position');
            $table->timestamps();
            $table->json('config_json')->nullable();
            $table->json('validation_json')->nullable();

            $table->index(['section_id']);
            $table->index(['survey_id']);
            $table->unique(['survey_id', 'position'], 'questions_survey_position_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
