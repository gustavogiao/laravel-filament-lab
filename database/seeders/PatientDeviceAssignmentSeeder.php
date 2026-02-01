<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Telemetry\Models\PatientDeviceAssignment;
use Illuminate\Database\Seeder;

final class PatientDeviceAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PatientDeviceAssignment::factory(2)->create();
    }
}
