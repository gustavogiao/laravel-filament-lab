<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Resources\ProjectResource\Pages;

use App\Modules\Project\Actions\CreateProjectAction;
use App\Modules\Project\DTOs\ProjectData;
use App\Modules\Project\Filament\Resources\ProjectResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateProject extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateProjectAction::class)
            ->execute(ProjectData::fromArray($data));
    }
}
