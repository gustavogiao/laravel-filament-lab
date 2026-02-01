<?php

namespace App\Modules\Telemetry\Filament\Resources\Patients\Pages;

use App\Modules\Telemetry\Actions\CreatePatientAction;
use App\Modules\Telemetry\DTOs\PatientData;
use App\Modules\Telemetry\Filament\Resources\Patients\PatientResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreatePatient extends CreateRecord
{
    protected static string $resource = PatientResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreatePatientAction::class)
            ->execute(PatientData::fromArray($data));
    }
}
