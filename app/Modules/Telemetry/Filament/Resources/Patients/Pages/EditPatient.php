<?php

namespace App\Modules\Telemetry\Filament\Resources\Patients\Pages;

use App\Modules\Shared\Filament\Concerns\RedirectsToIndex;
use App\Modules\Telemetry\Actions\UpdatePatientAction;
use App\Modules\Telemetry\DTOs\PatientData;
use App\Modules\Telemetry\Filament\Resources\Patients\PatientResource;
use App\Modules\Telemetry\Models\Patient;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditPatient extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = PatientResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Patient
    {
        /** @var Patient $record */
        return app(UpdatePatientAction::class)
            ->execute($record, PatientData::fromArray($data));
    }
}
