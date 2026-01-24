<?php

namespace App\Modules\Task\Filament\Resources\TaskResource\Pages;

use App\Modules\Task\Actions\CreateTaskAction;
use App\Modules\Task\DTOs\TaskData;
use App\Modules\Task\Filament\Resources\TaskResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

final class CreateTask extends CreateRecord
{
    protected static string $resource = TaskResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateTaskAction::class)
            ->execute(TaskData::fromArray($data));
    }
}
