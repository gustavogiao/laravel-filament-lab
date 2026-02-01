<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Telemetry\Models\VitalSignReading;
use Illuminate\Database\Seeder;

final class VitalSignReadingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        VitalSignReading::factory(5)->create();
    }
}
