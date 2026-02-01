<?php

namespace Database\Factories;

use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\VitalSignReading;
use Illuminate\Database\Eloquent\Factories\Factory;
use RuntimeException;

/**
 * @extends Factory<VitalSignReading>
 */
final class VitalSignReadingFactory extends Factory
{

    protected $model = VitalSignReading::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $patient = Patient::query()
            ->whereHas('devices', function ($q) {
                $q->where('patient_device_assignments.is_active', true);
            })
            ->inRandomOrder()
            ->firstOrFail();

        if (! $patient || ! $patient->activeDevice()) {
            throw new RuntimeException('No patient with active device found.');
        }

        return [
            'patient_id' => $patient->id,
            'device_id' => $patient->activeDevice()->id,
            'recorded_at' => $this->faker->dateTimeBetween('-1 years'),
            'heart_rate' => $this->faker->randomFloat(2, 60, 100),
            'blood_pressure_systolic' => $this->faker->randomFloat(2, 90, 120),
            'blood_pressure_diastolic' => $this->faker->randomFloat(2, 60, 80),
            'respiratory_rate' => $this->faker->randomFloat(2, 12, 20),
            'body_temperature' => $this->faker->randomFloat(2, 36.5, 37.5),
        ];
    }
}
