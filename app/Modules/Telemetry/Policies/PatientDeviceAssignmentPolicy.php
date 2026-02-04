<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Policies;

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Telemetry\Models\PatientDeviceAssignment;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class PatientDeviceAssignmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(
            PermissionsEnum::ViewAny->buildPermissionFor(PatientDeviceAssignment::class)
        );
    }

    public function view(User $user, PatientDeviceAssignment $assignment): bool
    {
        return $user->can(
            PermissionsEnum::View->buildPermissionFor(PatientDeviceAssignment::class)
        );
    }

    public function create(User $user): bool
    {
        return $user->can(
            PermissionsEnum::Create->buildPermissionFor(PatientDeviceAssignment::class)
        );
    }

    public function update(User $user, PatientDeviceAssignment $assignment): bool
    {
        // Business rule: Cannot modify historical (completed) assignments
        // Once an assignment has been unassigned, it becomes a historical record
        // and should not be modified to maintain data integrity
        if ($assignment->unassigned_at !== null) {
            return false;
        }

        // Business rule: Cannot modify inactive assignments
        // Inactive assignments are historical records and should be preserved
        if (! $assignment->is_active) {
            return false;
        }

        return $user->can(
            PermissionsEnum::Update->buildPermissionFor(PatientDeviceAssignment::class)
        );
    }

    public function delete(User $user, PatientDeviceAssignment $assignment): bool
    {
        // Business rule: Can only delete active assignments
        // Inactive assignments are historical records and should be preserved
        // for audit and compliance purposes
        if (! $assignment->is_active) {
            return false;
        }

        return $user->can(
            PermissionsEnum::Delete->buildPermissionFor(PatientDeviceAssignment::class)
        );
    }
}
