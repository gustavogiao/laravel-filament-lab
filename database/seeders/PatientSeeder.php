<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Telemetry\Models\Patient;
use Illuminate\Database\Seeder;

final class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::factory(2)->create();
    }
}
