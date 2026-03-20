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
        Schema::create('answers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('response_id')->index();
            $table->unsignedBigInteger('question_id')->nullable()->index();
            $table->longText('value')->nullable();
            $table->timestamps();

            $table->index(['question_id'], 'idx_answers_question_id');
            $table->index(['response_id'], 'idx_answers_response_id');
            $table->unique(['response_id', 'question_id'], 'uq_answers_response_question');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
