<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Resources\Patients\RelationManagers;

use App\Modules\Telemetry\Models\Device;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

final class DevicesRelationManager extends RelationManager
{
    protected static string $relationship = 'deviceAssignments';

    protected static ?string $title = 'Devices';

    // to do refactor the table and form in their own classes and dtos etc

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('device.device_uid')
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
                    ->label('Active')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['assigned_at'] = now();

                        return $data;
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data, $record): array {
                        if ($record->is_active && ! $data['is_active']) {
                            $data['unassigned_at'] = now();
                        } elseif (! $record->is_active && $data['is_active']) {
                            $data['assigned_at'] = now();
                            $data['unassigned_at'] = null;
                        }

                        return $data;
                    }),
                DeleteAction::make(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('device_id')
                    ->label('Device')
                    ->options(fn () => Device::pluck('device_uid', 'id'))
                    ->searchable()
                    ->required()
                    ->hiddenOn('edit'),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true)
                    ->live(),
            ]);
    }
}
