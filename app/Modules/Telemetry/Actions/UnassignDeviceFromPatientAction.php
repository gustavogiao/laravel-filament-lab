<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Actions;

use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\PatientDeviceAssignment;

final class UnassignDeviceFromPatientAction
{
    public function execute(Patient $patient, string $deviceId): ?PatientDeviceAssignment
    {
        /** @var PatientDeviceAssignment|null $assignment */
        $assignment = $patient->deviceAssignments()
            ->where('device_id', $deviceId)
            ->where('is_active', true)
            ->first();

        if (! $assignment) {
            return null;
        }

        $assignment->update([
            'is_active' => false,
            'unassigned_at' => now(),
        ]);

        return $assignment;
    }
}
