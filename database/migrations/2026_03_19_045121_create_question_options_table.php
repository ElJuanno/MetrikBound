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
        Schema::create('question_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('question_id')->index('idx_question_options_question_id');
            $table->text('label');
            $table->text('value')->nullable();
            $table->integer('position')->index('idx_question_options_position');
            $table->timestamps();

            $table->index(['question_id'], 'qopts_question_id_index');
            $table->index(['question_id', 'position'], 'qopts_question_pos_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('question_options');
    }
};
