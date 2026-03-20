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
        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('survey_id')->index('idx_sections_survey_id');
            $table->string('title', 200)->nullable();
            $table->integer('position')->index('idx_sections_position');
            $table->timestamps();

            $table->index(['survey_id']);
            $table->unique(['survey_id', 'position'], 'sections_survey_position_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
