<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Actions;

use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\PatientDeviceAssignment;

final class AssignDeviceToPatientAction
{
    public function execute(Patient $patient, string $deviceId): PatientDeviceAssignment
    {
        $patient->deviceAssignments()
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'unassigned_at' => now(),
            ]);

        PatientDeviceAssignment::query()
            ->where('device_id', $deviceId)
            ->where('is_active', true)
            ->update([
                'is_active' => false,
                'unassigned_at' => now(),
            ]);

        return $patient->deviceAssignments()->create([
            'device_id' => $deviceId,
            'assigned_at' => now(),
            'is_active' => true,
        ]);
    }
}
