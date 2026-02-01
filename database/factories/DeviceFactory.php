<?php

namespace Database\Factories;

use App\Modules\Telemetry\Models\Device;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Device>
 */
final class DeviceFactory extends Factory
{

    protected $model = Device::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'device_uid' => $this->faker->unique()->lexify('device_??????????'),
            'api_token_hash' => hash('sha256', $this->faker->unique()->regexify('[A-Za-z0-9]{40}')),
        ];
    }
}
