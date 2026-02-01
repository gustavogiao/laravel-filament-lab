<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Observers;

use App\Modules\Telemetry\Models\PatientDeviceAssignment;

final class PatientDeviceAssignmentObserver
{
    public function creating(PatientDeviceAssignment $assignment): void
    {
        if ($assignment->is_active) {
            $this->deactivateOtherAssignments($assignment);
        }
    }

    public function updating(PatientDeviceAssignment $assignment): void
    {
        if ($assignment->is_active && $assignment->isDirty('is_active')) {
            $this->deactivateOtherAssignments($assignment);
        }
    }

    private function deactivateOtherAssignments(PatientDeviceAssignment $assignment): void
    {
        PatientDeviceAssignment::query()
            ->where('patient_id', $assignment->patient_id)
            ->where('is_active', true)
            ->when($assignment->exists, fn ($query) => $query->whereKeyNot($assignment->id))
            ->update([
                'is_active' => false,
                'unassigned_at' => now(),
            ]);

        PatientDeviceAssignment::query()
            ->where('device_id', $assignment->device_id)
            ->where('is_active', true)
            ->when($assignment->exists, fn ($query) => $query->whereKeyNot($assignment->id))
            ->update([
                'is_active' => false,
                'unassigned_at' => now(),
            ]);
    }
}
