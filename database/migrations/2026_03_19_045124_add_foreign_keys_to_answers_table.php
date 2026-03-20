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
        Schema::table('answers', function (Blueprint $table) {
            $table->foreign(['question_id'])->references(['id'])->on('questions')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['response_id'])->references(['id'])->on('responses')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['question_id'], 'fk_answers_question')->references(['id'])->on('questions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['response_id'], 'fk_answers_response')->references(['id'])->on('responses')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('answers', function (Blueprint $table) {
            $table->dropForeign('answers_question_id_foreign');
            $table->dropForeign('answers_response_id_foreign');
            $table->dropForeign('fk_answers_question');
            $table->dropForeign('fk_answers_response');
        });
    }
};
