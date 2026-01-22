<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Resources\ProjectResource\Pages;

use App\Modules\Project\Actions\UpdateProjectAction;
use App\Modules\Project\DTOs\ProjectData;
use App\Modules\Project\Filament\Resources\ProjectResource;
use App\Modules\Project\Models\Project;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

final class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Project
    {
        /** @var Project $record */
        return app(UpdateProjectAction::class)
            ->execute($record, ProjectData::fromArray($data));
    }
}
