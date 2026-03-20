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
        Schema::table('questions', function (Blueprint $table) {
            $table->foreign(['section_id'], 'fk_questions_section')->references(['id'])->on('sections')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['survey_id'], 'fk_questions_survey')->references(['id'])->on('surveys')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['section_id'])->references(['id'])->on('sections')->onUpdate('restrict')->onDelete('set null');
            $table->foreign(['survey_id'])->references(['id'])->on('surveys')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign('fk_questions_section');
            $table->dropForeign('fk_questions_survey');
            $table->dropForeign('questions_section_id_foreign');
            $table->dropForeign('questions_survey_id_foreign');
        });
    }
};
