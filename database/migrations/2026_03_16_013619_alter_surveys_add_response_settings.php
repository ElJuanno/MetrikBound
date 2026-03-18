<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->enum('response_mode', ['anonymous', 'registered', 'both'])
                ->default('anonymous')
                ->after('status');

            $table->boolean('allow_multiple_responses')
                ->default(true)
                ->after('response_mode');

            $table->timestamp('starts_at')->nullable()->after('allow_multiple_responses');
            $table->timestamp('ends_at')->nullable()->after('starts_at');
        });
    }

    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn([
                'response_mode',
                'allow_multiple_responses',
                'starts_at',
                'ends_at',
            ]);
        });
    }
};