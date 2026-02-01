<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Telemetry\Models\Device;
use Illuminate\Database\Seeder;

final class DeviceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Device::factory(10)->create();
    }
}
