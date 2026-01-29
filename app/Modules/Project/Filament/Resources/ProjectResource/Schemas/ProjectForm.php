<?php

declare(strict_types=1);

namespace App\Modules\Project\Filament\Resources\ProjectResource\Schemas;

use App\Modules\Project\Enums\ProjectStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Textarea::make('description')
                            ->rows(4),

                        Select::make('status')
                            ->options(ProjectStatus::class),
                    ])
                    ->columns(1),
            ]);
    }
}
