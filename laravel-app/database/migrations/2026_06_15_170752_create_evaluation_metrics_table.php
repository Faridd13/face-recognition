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
        Schema::create('evaluation_metrics', function (Blueprint $table) {
            $table->id();
            $table->integer('total_tests')->default(0);
            $table->integer('correct_predictions')->default(0);
            $table->decimal('accuracy', 5, 2)->nullable();
            $table->decimal('precision', 5, 2)->nullable();
            $table->decimal('recall', 5, 2)->nullable();
            $table->decimal('far', 5, 2)->nullable();
            $table->decimal('frr', 5, 2)->nullable();
            $table->decimal('avg_latency', 10, 3)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_metrics');
    }
};
