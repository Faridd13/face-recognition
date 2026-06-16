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
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->date('attendance_date');
            $table->time('attendance_time');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpha'])->default('hadir');
            $table->decimal('confidence', 5, 2)->nullable();
            $table->decimal('latency', 10, 3)->nullable();
            $table->string('light_condition')->nullable();
            $table->string('face_angle')->nullable();
            $table->string('distance_condition')->nullable();
            $table->string('session_id')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'attendance_date', 'session_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance');
    }
};
