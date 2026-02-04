<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Resources\VitalSignReading;

use App\Modules\Telemetry\Filament\Resources\VitalSignReading\Pages\PatientDashboard;
use App\Modules\Telemetry\Models\VitalSignReading;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

final class VitalSignReadingResource extends Resource
{
    protected static ?string $model = VitalSignReading::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::Heart;

    protected static ?string $modelLabel = 'Vital Signal';

    protected static ?string $pluralModelLabel = 'Vital Signals';

    public static function getNavigationGroup(): string
    {
        return 'Telemetry';
    }

    public static function getPages(): array
    {
        return [
            'index' => PatientDashboard::route('/'),
        ];
    }
}
