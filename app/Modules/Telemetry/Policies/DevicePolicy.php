<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Policies;

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Telemetry\Models\Device;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class DevicePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(
            PermissionsEnum::ViewAny->buildPermissionFor(Device::class)
        );
    }

    public function view(User $user, Device $device): bool
    {
        return $user->can(
            PermissionsEnum::View->buildPermissionFor(Device::class)
        );
    }

    public function create(User $user): bool
    {
        return $user->can(
            PermissionsEnum::Create->buildPermissionFor(Device::class)
        );
    }

    public function update(User $user, Device $device): bool
    {
        $activePatient = $device->activePatient();

        if ($activePatient !== null) {
            return false;
        }

        return $user->can(
            PermissionsEnum::Update->buildPermissionFor(Device::class)
        );
    }

    public function delete(User $user, Device $device): bool
    {
        $activePatient = $device->activePatient();

        if ($activePatient !== null) {
            return false;
        }

        if ($device->vitalSignReadings()->exists()) {
            return false;
        }

        return $user->can(
            PermissionsEnum::Delete->buildPermissionFor(Device::class)
        );
    }
}
