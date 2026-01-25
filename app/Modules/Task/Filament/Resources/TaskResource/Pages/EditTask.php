<?php

namespace App\Modules\Task\Filament\Resources\TaskResource\Pages;

use App\Modules\Shared\Filament\Concerns\RedirectsToIndex;
use App\Modules\Task\Actions\UpdateTaskAction;
use App\Modules\Task\DTOs\TaskData;
use App\Modules\Task\Filament\Resources\TaskResource;
use App\Modules\Task\Models\Task;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

final class EditTask extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = TaskResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Task
    {
        $data['submitted_by_id'] = auth()->id();
        /** @var Task $record */
        return app(UpdateTaskAction::class)
            ->execute($record, TaskData::fromArray($data));
    }
}
