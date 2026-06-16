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
        Schema::table('evaluation_metrics', function (Blueprint $table) {
            $table->foreignId('experiment_log_id')->nullable()->constrained('experiment_logs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluation_metrics', function (Blueprint $table) {
            $table->dropForeign(['experiment_log_id']);
            $table->dropColumn('experiment_log_id');
        });
    }
};
