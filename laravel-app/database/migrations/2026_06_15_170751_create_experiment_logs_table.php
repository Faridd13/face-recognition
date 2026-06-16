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
        Schema::create('experiment_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->string('actual_identity');
            $table->string('predicted_identity')->nullable();
            $table->decimal('confidence', 5, 2)->nullable();
            $table->decimal('latency', 10, 3)->nullable();
            $table->string('light_condition')->nullable();
            $table->string('face_angle')->nullable();
            $table->string('distance_condition')->nullable();
            $table->boolean('is_correct');
            $table->enum('experiment_type', ['training', 'testing']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiment_logs');
    }
};
