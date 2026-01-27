<?php

declare(strict_types=1);

namespace App\Modules\User\Policies;

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Permissions\Enums\Roles;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class UserPolicy
{
    use HandlesAuthorization;

    public function view(User $user, User $model): bool
    {
        return $user->can(PermissionsEnum::View->buildPermissionFor(User::class));
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionsEnum::Create->buildPermissionFor(User::class));
    }

    public function update(User $user, User $model): bool
    {
        return $user->can(PermissionsEnum::Update->buildPermissionFor(User::class));
    }

    public function delete(User $user, User $model): bool
    {
        return $user->can(PermissionsEnum::Delete->buildPermissionFor(User::class));
    }

    public function block(User $user, User $model): bool
    {
        return $user->can(PermissionsEnum::Update->buildPermissionFor(User::class))
            && ! $model->hasRole(Roles::SuperAdmin);
    }

    public function restore(User $user, User $model): bool
    {
        return $user->can(PermissionsEnum::Restore->buildPermissionFor(User::class));
    }

    public function forceDelete(User $user, User $model): bool
    {
        return $user->can(PermissionsEnum::ForceDelete->buildPermissionFor(User::class));
    }
}
