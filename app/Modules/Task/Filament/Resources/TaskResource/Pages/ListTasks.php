<?php

namespace App\Modules\Task\Filament\Resources\TaskResource\Pages;

use App\Modules\Task\Filament\Resources\TaskResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

final class ListTasks extends ListRecords
{
    protected static string $resource = TaskResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
