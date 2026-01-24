<?php

namespace App\Modules\Task\Filament\Resources\TaskResource\Pages;

use App\Modules\Task\Actions\UpdateTaskAction;
use App\Modules\Task\DTOs\TaskData;
use App\Modules\Task\Filament\Resources\TaskResource;
use App\Modules\Task\Models\Task;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

final class EditTask extends EditRecord
{
    protected static string $resource = TaskResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Task
    {
        /** @var Task $record */
        return app(UpdateTaskAction::class)
            ->execute($record, TaskData::fromArray($data));
    }
}
