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
        Schema::table('survey_blocks', function (Blueprint $table) {
            $table->integer('x')->nullable()->after('position');
            $table->integer('y')->nullable()->after('x');
            $table->integer('width')->nullable()->after('y');
            $table->integer('height')->nullable()->after('width');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_blocks', function (Blueprint $table) {
            $table->dropColumn(['x', 'y', 'width', 'height']);
        });
    }
};
