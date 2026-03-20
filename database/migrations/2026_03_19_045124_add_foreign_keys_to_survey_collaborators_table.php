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
        Schema::table('survey_collaborators', function (Blueprint $table) {
            $table->foreign(['survey_id'], 'collaborators_survey_id_foreign')->references(['id'])->on('surveys')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'], 'collaborators_user_id_foreign')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_collaborators', function (Blueprint $table) {
            $table->dropForeign('collaborators_survey_id_foreign');
            $table->dropForeign('collaborators_user_id_foreign');
        });
    }
};
