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
    Schema::table('surveys', function (Blueprint $table) {
        $table->json('builder_state')->nullable();   // snapshot del editor
        $table->timestamp('last_saved_at')->nullable();
    });
}

public function down(): void
{
    Schema::table('surveys', function (Blueprint $table) {
        $table->dropColumn(['builder_state', 'last_saved_at']);
    });
}

};
