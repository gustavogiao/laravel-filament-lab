<?php

declare(strict_types=1);

namespace App\Modules\Task\Filament\Resources;

use App\Modules\Task\Filament\Resources\TaskResource\Pages\CreateTask;
use App\Modules\Task\Filament\Resources\TaskResource\Pages\EditTask;
use App\Modules\Task\Filament\Resources\TaskResource\Pages\ListTasks;
use App\Modules\Task\Filament\Resources\TaskResource\Pages\ViewTask;
use App\Modules\Task\Filament\Resources\TaskResource\Schemas\TaskForm;
use App\Modules\Task\Filament\Resources\TaskResource\Tables\TaskTable;
use App\Modules\Task\Models\Task;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationLabel = 'Tasks';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CheckCircle;

    protected static ?string $slug = 'tasks';

    public static function form(Schema $schema): Schema
    {
        return TaskForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaskTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTasks::route('/'),
            'create' => CreateTask::route('/create'),
            'view' => ViewTask::route('/{record}'),
            'edit' => EditTask::route('/{record}/edit'),
        ];
    }
}
