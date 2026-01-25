<?php

declare(strict_types=1);

namespace App\Modules\Project\Policies;

use App\Modules\Permissions\Enums\PermissionsEnum;
use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

final class ProjectPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Project $project): bool
    {
        return
            $user->can(PermissionsEnum::View->buildPermissionFor(Project::class))
            && $project->owner_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->can(PermissionsEnum::Create->buildPermissionFor(Project::class));
    }

    public function update(User $user, Project $project): bool
    {
        if ($project->is_archived) {
            return false;
        }

        return
            $user->can(PermissionsEnum::Update->buildPermissionFor(Project::class))
            && $project->owner_id === $user->id;
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can(PermissionsEnum::Delete->buildPermissionFor(Project::class));
    }

    public function archive(User $user, Project $project): bool
    {
        if ($project->is_archived) {
            return false;
        }

        return
            $user->can(PermissionsEnum::Update->buildPermissionFor(Project::class))
            && $project->owner_id === $user->id;
    }
}
