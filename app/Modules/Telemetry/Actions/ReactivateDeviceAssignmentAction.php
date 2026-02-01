<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Actions;

use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\PatientDeviceAssignment;

final class ReactivateDeviceAssignmentAction
{
    public function execute(
        Patient $patient,
        PatientDeviceAssignment $assignment
    ): PatientDeviceAssignment {
        $patient->deviceAssignments()
            ->where('is_active', true)
            ->whereKeyNot($assignment->id)
            ->update([
                'is_active' => false,
                'unassigned_at' => now(),
            ]);

        PatientDeviceAssignment::query()
            ->where('device_id', $assignment->device_id)
            ->where('is_active', true)
            ->whereKeyNot($assignment->id)
            ->update([
                'is_active' => false,
                'unassigned_at' => now(),
            ]);

        $assignment->update([
            'is_active' => true,
            'assigned_at' => now(),
            'unassigned_at' => null,
        ]);

        return $assignment;
    }
}
