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
        Schema::table('survey_drafts', function (Blueprint $table) {
            $table->foreign(['survey_id'], 'fk_drafts_survey')->references(['id'])->on('surveys')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_drafts_user')->references(['id'])->on('users')->onUpdate('restrict')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_drafts', function (Blueprint $table) {
            $table->dropForeign('fk_drafts_survey');
            $table->dropForeign('fk_drafts_user');
        });
    }
};
