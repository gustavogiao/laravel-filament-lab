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
        Schema::create('vital_sign_readings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('patient_id');
            $table->uuid('device_id');
            $table->timestamp('recorded_at')->useCurrent();
            $table->decimal('heart_rate')->nullable();
            $table->decimal('blood_pressure_systolic')->nullable();
            $table->decimal('blood_pressure_diastolic')->nullable();
            $table->decimal('respiratory_rate')->nullable();
            $table->decimal('body_temperature')->nullable();
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('patients')->cascadeOnDelete();
            $table->foreign('device_id')->references('id')->on('devices')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_sign_readings');
    }
};
