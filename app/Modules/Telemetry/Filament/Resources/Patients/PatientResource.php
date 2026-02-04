<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Resources\Patients;

use App\Modules\Telemetry\Filament\Resources\Patients\Pages\CreatePatient;
use App\Modules\Telemetry\Filament\Resources\Patients\Pages\EditPatient;
use App\Modules\Telemetry\Filament\Resources\Patients\Pages\ListPatients;
use App\Modules\Telemetry\Filament\Resources\Patients\Pages\ViewPatient;
use App\Modules\Telemetry\Filament\Resources\Patients\RelationManagers\DevicesRelationManager;
use App\Modules\Telemetry\Filament\Resources\Patients\Schemas\PatientForm;
use App\Modules\Telemetry\Filament\Resources\Patients\Tables\PatientsTable;
use App\Modules\Telemetry\Models\Patient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

final class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::UserGroup;

    protected static ?string $recordTitleAttribute = 'first_name';

    public static function getNavigationGroup(): string
    {
        return 'Telemetry';
    }

    public static function form(Schema $schema): Schema
    {
        return PatientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PatientsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            DevicesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPatients::route('/'),
            'create' => CreatePatient::route('/create'),
            'view' => ViewPatient::route('/{record}'),
            'edit' => EditPatient::route('/{record}/edit'),
        ];
    }
}
