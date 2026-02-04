<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Policies;

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Telemetry\Models\Patient;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class PatientPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(
            PermissionsEnum::ViewAny->buildPermissionFor(Patient::class)
        );
    }

    public function view(User $user, Patient $patient): bool
    {
        return $user->can(
            PermissionsEnum::View->buildPermissionFor(Patient::class)
        );
    }

    public function create(User $user): bool
    {
        return $user->can(
            PermissionsEnum::Create->buildPermissionFor(Patient::class)
        );
    }

    public function update(User $user, Patient $patient): bool
    {
        return $user->can(
            PermissionsEnum::Update->buildPermissionFor(Patient::class)
        );
    }

    public function delete(User $user, Patient $patient): bool
    {
        if ($patient->hasActiveDevice()) {
            return false;
        }

        if ($patient->vitalSignReadings()->exists()) {
            return false;
        }

        return $user->can(
            PermissionsEnum::Delete->buildPermissionFor(Patient::class)
        );
    }
}
