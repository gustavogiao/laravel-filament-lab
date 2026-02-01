<?php

namespace App\Modules\Telemetry\Filament\Resources\Patients\Pages;

use App\Modules\Telemetry\Filament\Resources\Patients\PatientResource;
use Filament\Resources\Pages\ViewRecord;

class ViewPatient extends ViewRecord
{
    protected static string $resource = PatientResource::class;
}
