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
        Schema::create('survey_collaborators', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('survey_id');
            $table->unsignedBigInteger('user_id')->index('collaborators_user_id_index');
            $table->enum('role', ['editor', 'viewer'])->default('editor');
            $table->timestamp('created_at')->nullable();

            $table->unique(['survey_id', 'user_id'], 'collaborators_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_collaborators');
    }
};
