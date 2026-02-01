<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Resources;

use App\Modules\Project\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Modules\Project\Filament\Resources\ProjectResource\Pages\EditProject;
use App\Modules\Project\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Modules\Project\Filament\Resources\ProjectResource\Pages\ViewProject;
use App\Modules\Project\Filament\Resources\ProjectResource\RelationManagers\TasksRelationManager;
use App\Modules\Project\Filament\Resources\ProjectResource\Schemas\ProjectForm;
use App\Modules\Project\Filament\Resources\ProjectResource\Tables\ProjectTable;
use App\Modules\Project\Models\Project;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class ProjectResource extends Resource
{
    protected static ?string $model = Project::class;

    protected static ?string $navigationLabel = 'Projects';

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Briefcase;

    protected static ?string $slug = 'projects';

    public static function getRelations(): array
    {
        return [
            TasksRelationManager::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return ProjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProjectTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProjects::route('/'),
            'create' => CreateProject::route('/create'),
            'view' => ViewProject::route('/{record}'),
            'edit' => EditProject::route('/{record}/edit'),
        ];
    }
}
