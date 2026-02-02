<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Resources\VitalSignReading\Pages;

use App\Modules\Telemetry\Filament\Resources\VitalSignReading\VitalSignReadingResource;
use Filament\Resources\Pages\ListRecords;

final class ListVitalSignReadings extends ListRecords
{
    protected static string $resource = VitalSignReadingResource::class;
}
