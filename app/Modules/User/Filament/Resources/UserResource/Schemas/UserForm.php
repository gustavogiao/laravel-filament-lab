<?php

namespace App\Modules\User\Filament\Resources\UserResource\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Admin')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('password')
                            ->password()
                            ->visible(fn ($livewire) => $livewire instanceof CreateRecord)
                            ->required(fn ($livewire) => $livewire instanceof CreateRecord)
                            ->minLength(8)
                            ->maxLength(255)
                            ->dehydrateStateUsing(fn ($state) => $state ? bcrypt($state) : null)
                            ->label('Password'),
                    ]),
            ]);
    }
}
