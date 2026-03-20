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
        Schema::create('survey_access', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('survey_id')->index();
            $table->string('token', 100)->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_access');
    }
};
