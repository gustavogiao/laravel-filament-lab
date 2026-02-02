<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Tables;

use App\Modules\Telemetry\Models\VitalSignReading;
use Filament\Tables\Columns\TextColumn;

final class LatestReadingsTableSchema
{
    public static function columns(): array
    {
        return [
            TextColumn::make('recorded_at')
                ->label('Date/Time')
                ->dateTime('d/m/Y H:i')
                ->sortable(),

            TextColumn::make('heart_rate')
                ->label('FC (bpm)')
                ->badge()
                ->color(fn ($state) => match (true) {
                    $state === null => 'gray',
                    $state >= 60 && $state <= 100 => 'success',
                    default => 'danger',
                })
                ->placeholder('-'),

            TextColumn::make('blood_pressure')
                ->label('PA (mmHg)')
                ->state(fn (VitalSignReading $record) => $record->blood_pressure_systolic && $record->blood_pressure_diastolic
                    ? "{$record->blood_pressure_systolic}/{$record->blood_pressure_diastolic}"
                    : '-'
                )
                ->badge()
                ->color(fn (VitalSignReading $record) => match (true) {
                    $record->blood_pressure_systolic === null => 'gray',
                    $record->blood_pressure_systolic <= 120 && $record->blood_pressure_diastolic <= 80 => 'success',
                    $record->blood_pressure_systolic <= 140 && $record->blood_pressure_diastolic <= 90 => 'warning',
                    default => 'danger',
                }),

            TextColumn::make('respiratory_rate')
                ->label('FR (rpm)')
                ->badge()
                ->color(fn ($state) => match (true) {
                    $state === null => 'gray',
                    $state >= 12 && $state <= 20 => 'success',
                    default => 'warning',
                })
                ->placeholder('-'),

            TextColumn::make('body_temperature')
                ->label('Temp (°C)')
                ->badge()
                ->color(fn ($state) => match (true) {
                    $state === null => 'gray',
                    $state >= 36.1 && $state <= 37.2 => 'success',
                    default => 'danger',
                })
                ->placeholder('-'),

            TextColumn::make('device.device_uid')
                ->label('Device')
                ->placeholder('-'),
        ];
    }
}
