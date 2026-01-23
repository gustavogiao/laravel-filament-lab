<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Resources\ProjectResource\RelationManagers;

use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;

final class TasksRelationManager extends RelationManager
{
    protected static string $relationship = 'tasks';

    protected static ?string $title = 'Tasks';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('title')
                ->required()
                ->maxLength(255),

            Forms\Components\Textarea::make('description')
                ->rows(3),

            Forms\Components\Toggle::make('is_completed')
                ->label('Completed'),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_completed')
                    ->boolean()
                    ->label('Done'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->since(),
            ])
            ->headerActions([
                Actions\CreateAction::make(),
            ])
            ->recordActions([
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
            ]);
    }
}
