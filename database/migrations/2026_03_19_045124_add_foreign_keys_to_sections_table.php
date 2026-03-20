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
        Schema::table('sections', function (Blueprint $table) {
            $table->foreign(['survey_id'], 'fk_sections_survey')->references(['id'])->on('surveys')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['survey_id'])->references(['id'])->on('surveys')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign('fk_sections_survey');
            $table->dropForeign('sections_survey_id_foreign');
        });
    }
};
