<?php

namespace App\Modules\Telemetry\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()
                    ->schema([
                        TextInput::make('first_name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('last_name')
                            ->required()
                            ->maxLength(255),
                        DatePicker::make('date_of_birth')
                            ->required(),
                        TextInput::make('gender')
                            ->required()
                            ->maxLength(50),
                    ])
                    ->columns(1),
            ]);
    }
}
