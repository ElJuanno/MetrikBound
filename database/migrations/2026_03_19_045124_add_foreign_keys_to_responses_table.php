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
        Schema::table('responses', function (Blueprint $table) {
            $table->foreign(['survey_id'], 'fk_responses_survey')->references(['id'])->on('surveys')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['user_id'], 'fk_responses_user')->references(['id'])->on('users')->onUpdate('cascade')->onDelete('set null');
            $table->foreign(['survey_id'])->references(['id'])->on('surveys')->onUpdate('restrict')->onDelete('cascade');
            $table->foreign(['user_id'])->references(['id'])->on('users')->onUpdate('restrict')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('responses', function (Blueprint $table) {
            $table->dropForeign('fk_responses_survey');
            $table->dropForeign('fk_responses_user');
            $table->dropForeign('responses_survey_id_foreign');
            $table->dropForeign('responses_user_id_foreign');
        });
    }
};
