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
        Schema::table('question_options', function (Blueprint $table) {
            $table->foreign(['question_id'], 'fk_question_options_question')->references(['id'])->on('questions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['question_id'], 'qopts_question_id_foreign')->references(['id'])->on('questions')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('question_options', function (Blueprint $table) {
            $table->dropForeign('fk_question_options_question');
            $table->dropForeign('qopts_question_id_foreign');
        });
    }
};
