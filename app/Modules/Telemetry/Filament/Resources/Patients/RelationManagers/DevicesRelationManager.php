<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Resources\Patients\RelationManagers;

use App\Modules\Telemetry\Actions\AssignDeviceToPatientAction;
use App\Modules\Telemetry\Actions\ReactivateDeviceAssignmentAction;
use App\Modules\Telemetry\Actions\UnassignDeviceFromPatientAction;
use App\Modules\Telemetry\Filament\Resources\DeviceAssignment\Schemas\DeviceAssignmentForm;
use App\Modules\Telemetry\Filament\Resources\DeviceAssignment\Tables\DeviceAssignmentTable;
use App\Modules\Telemetry\Models\Patient;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;

final class DevicesRelationManager extends RelationManager
{
    protected static string $relationship = 'deviceAssignments';

    protected static ?string $title = 'Devices';

    public function table(Table $table): Table
    {
        return DeviceAssignmentTable::make($table)
            ->headerActions([
                CreateAction::make()
                    ->label('Assign Device')
                    ->using(function (array $data): void {
                        /** @var Patient $patient */
                        $patient = $this->getOwnerRecord();

                        app(AssignDeviceToPatientAction::class)
                            ->execute($patient, $data['device_id']);
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Change Status')
                    ->using(function (array $data, $record): void {
                        /** @var Patient $patient */
                        $patient = $this->getOwnerRecord();

                        if ($record->is_active && ! $data['is_active']) {
                            app(UnassignDeviceFromPatientAction::class)
                                ->execute($patient, $record->device_id);
                        }

                        if (! $record->is_active && $data['is_active']) {
                            app(ReactivateDeviceAssignmentAction::class)
                                ->execute($patient, $record);
                        }
                    }),

                DeleteAction::make()
                    ->label('Remove Assignment')
                    ->requiresConfirmation(),
            ]);
    }

    public function form(Schema $schema): Schema
    {
        return DeviceAssignmentForm::make($schema);
    }
}
