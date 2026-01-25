<?php

declare(strict_types=1);

namespace App\Modules\Permissions\Policies;

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Permissions\Models\Role;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can(PermissionsEnum::ViewAny->buildPermissionFor(Role::class));
    }

    public function view(User $user, Role $role): bool
    {
        return $user->can(PermissionsEnum::View->buildPermissionFor(Role::class));
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionsEnum::Create->buildPermissionFor(Role::class));
    }

    public function update(User $user, Role $role): bool
    {
        return $user->can(PermissionsEnum::Update->buildPermissionFor(Role::class));
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->can(PermissionsEnum::Delete->buildPermissionFor(Role::class));
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->can(PermissionsEnum::Restore->buildPermissionFor(Role::class));
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $user->can(PermissionsEnum::ForceDelete->buildPermissionFor(Role::class));
    }
}
