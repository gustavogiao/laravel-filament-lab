<?php

namespace App\Modules\Telemetry\Filament\Resources\DeviceAssignment\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class DeviceAssignmentTable
{
    public static function make(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('device.device_uid')
                ->label('Device UID')
                ->searchable()
                ->sortable(),

            TextColumn::make('assigned_at')
                ->dateTime()
                ->sortable(),

            TextColumn::make('unassigned_at')
                ->dateTime()
                ->sortable()
                ->placeholder('N/A'),

            TextColumn::make('is_active')
                ->label('Status')
                ->badge()
                ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),

            TextColumn::make('created_at')
                ->dateTime()
                ->sortable(),

            TextColumn::make('updated_at')
                ->dateTime()
                ->sortable(),
        ]);
    }
}
