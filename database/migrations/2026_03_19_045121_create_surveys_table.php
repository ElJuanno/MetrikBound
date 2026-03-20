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
        Schema::create('surveys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('idx_surveys_user_id');
            $table->string('title', 200);
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft')->index('idx_surveys_status');
            $table->enum('response_mode', ['anonymous', 'registered', 'both'])->default('anonymous');
            $table->boolean('allow_multiple_responses')->default(true);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_public')->default(true);
            $table->enum('visibility', ['public', 'private', 'unlisted'])->default('public')->index('idx_surveys_visibility');
            $table->string('share_token', 64)->unique();
            $table->timestamps();
            $table->json('theme_json')->nullable();
            $table->json('settings_json')->nullable();
            $table->json('builder_state')->nullable();
            $table->timestamp('last_saved_at')->nullable();

            $table->index(['user_id']);
            $table->unique(['share_token'], 'uq_surveys_share_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
