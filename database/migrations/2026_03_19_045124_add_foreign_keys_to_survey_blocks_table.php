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
        Schema::table('survey_blocks', function (Blueprint $table) {
            $table->foreign(['question_id'], 'fk_blocks_question')->references(['id'])->on('questions')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['survey_id'], 'fk_blocks_survey')->references(['id'])->on('surveys')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_blocks', function (Blueprint $table) {
            $table->dropForeign('fk_blocks_question');
            $table->dropForeign('fk_blocks_survey');
        });
    }
};
