<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Policies;

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Telemetry\Models\VitalSignReading;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class VitalSignReadingPolicy
{
    use HandlesAuthorization;

    /**
     * Time window (in hours) during which vital sign readings can be edited.
     * After this period, medical records become immutable.
     */
    private const EDIT_WINDOW_HOURS = 1;

    /**
     * Time window (in minutes) during which erroneous vital sign readings can be deleted.
     * This short window allows correction of immediate data entry errors.
     */
    private const DELETE_WINDOW_MINUTES = 15;

    public function viewAny(User $user): bool
    {
        return $user->can(
            PermissionsEnum::ViewAny->buildPermissionFor(VitalSignReading::class)
        );
    }

    public function view(User $user, VitalSignReading $vitalSignReading): bool
    {
        return $user->can(
            PermissionsEnum::View->buildPermissionFor(VitalSignReading::class)
        );
    }

    public function create(User $user): bool
    {
        return $user->can(
            PermissionsEnum::Create->buildPermissionFor(VitalSignReading::class)
        );
    }

    public function update(User $user, VitalSignReading $vitalSignReading): bool
    {
        if ($vitalSignReading->created_at->diffInHours(now()) > self::EDIT_WINDOW_HOURS) {
            return false;
        }

        return $user->can(
            PermissionsEnum::Update->buildPermissionFor(VitalSignReading::class)
        );
    }

    public function delete(User $user, VitalSignReading $vitalSignReading): bool
    {
        if ($vitalSignReading->created_at->diffInMinutes(now()) > self::DELETE_WINDOW_MINUTES) {
            return false;
        }

        return $user->can(
            PermissionsEnum::Delete->buildPermissionFor(VitalSignReading::class)
        );
    }
}
