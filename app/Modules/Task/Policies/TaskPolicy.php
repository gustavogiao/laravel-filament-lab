<?php

declare(strict_types=1);

namespace App\Modules\Task\Policies;

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class TaskPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Task $task): bool
    {
        return
            $user->can(PermissionsEnum::View->buildPermissionFor(Task::class))
            && ($task->submitted_by_id === $user->id || $task->project->owner_id === $user->id);
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionsEnum::Create->buildPermissionFor(Task::class));
    }

    public function update(User $user, Task $task): bool
    {
        return
            $user->can(PermissionsEnum::Update->buildPermissionFor(Task::class))
            && ($task->submitted_by_id === $user->id || $task->project->owner_id === $user->id);
    }

    public function delete(User $user, Task $task): bool
    {
        return
            $user->can(PermissionsEnum::Delete->buildPermissionFor(Task::class))
            && ($task->submitted_by_id === $user->id || $task->project->owner_id === $user->id);
    }

    public function restore(User $user, Task $task): bool
    {
        return
            $user->can(PermissionsEnum::Restore->buildPermissionFor(Task::class))
            && ($task->submitted_by_id === $user->id || $task->project->owner_id === $user->id);
    }

    public function forceDelete(User $user, Task $task): bool
    {
        return
            $user->can(PermissionsEnum::ForceDelete->buildPermissionFor(Task::class))
            && ($task->submitted_by_id === $user->id || $task->project->owner_id === $user->id);
    }
}
