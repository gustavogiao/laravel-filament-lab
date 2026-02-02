<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\VitalSignReading;
use Illuminate\Database\Seeder;

final class VitalSignReadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $patients = Patient::query()
            ->whereHas('devices', function ($q) {
                $q->where('patient_device_assignments.is_active', true);
            })
            ->with('devices')
            ->get();

        // Create readings for each patient
        foreach ($patients as $patient) {
            $device = $patient->activeDevice();

            // Create 30 readings spread over the last 24 hours
            for ($i = 0; $i < 30; $i++) {
                $recordedAt = now()->subHours(24 - ($i * 0.8))->subMinutes(rand(0, 30));

                // Simulate realistic variations
                $baseHeartRate = 75;
                $baseSystolic = 120;
                $baseDiastolic = 80;
                $baseRespRate = 16;
                $baseTemp = 36.8;

                VitalSignReading::create([
                    'patient_id' => $patient->id,
                    'device_id' => $device->id,
                    'recorded_at' => $recordedAt,
                    'heart_rate' => $baseHeartRate + rand(-10, 15),
                    'blood_pressure_systolic' => $baseSystolic + rand(-15, 20),
                    'blood_pressure_diastolic' => $baseDiastolic + rand(-10, 10),
                    'respiratory_rate' => $baseRespRate + rand(-3, 4),
                    'body_temperature' => $baseTemp + (rand(-5, 8) / 10),
                ]);
            }
        }
    }
}
