<?php

declare(strict_types=1);

namespace App\Modules\Project\Actions;

use App\Modules\Project\DTOs\ProjectData;
use App\Modules\Project\Models\Project;

final class CreateProjectAction
{
    public function execute(ProjectData $data): Project
    {
        $project = new Project;

        $project->name = $data->name;
        $project->description = $data->description;
        $project->is_active = $data->isActive;
        $project->owner_id = $data->owner_id;

        $project->save();

        return $project;
    }
}
