<?php

namespace App\Modules\Telemetry\Filament\Resources\DeviceAssignment\Schemas;

use App\Modules\Telemetry\Models\Device;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

final class DeviceAssignmentForm
{
    public static function make(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('device_id')
                ->label('Device')
                ->options(fn () => Device::pluck('device_uid', 'id'))
                ->searchable()
                ->required()
                ->hiddenOn('edit'),

            Toggle::make('is_active')
                ->label('Active')
                ->default(true)
                ->live()
                ->columnSpanFull(),
        ]);
    }
}
