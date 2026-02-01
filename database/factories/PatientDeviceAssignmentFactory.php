<?php

namespace Database\Factories;

use App\Modules\Telemetry\Models\Device;
use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\PatientDeviceAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PatientDeviceAssignment>
 */
final class PatientDeviceAssignmentFactory extends Factory
{
    protected $model = PatientDeviceAssignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'patient_id' => Patient::factory(),
            'device_id' => Device::factory(),
            'assigned_at' => $this->faker->dateTimeBetween('-1 years'),
            'unassigned_at' => null,
            'is_active' => true,
        ];
    }
}
