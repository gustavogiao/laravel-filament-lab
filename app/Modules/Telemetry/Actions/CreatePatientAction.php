<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Actions;

use App\Modules\Telemetry\DTOs\PatientData;
use App\Modules\Telemetry\Models\Patient;

final class CreatePatientAction
{
    public function execute(PatientData $data): Patient
    {
        $patient = new Patient;

        $patient->first_name = $data->first_name;
        $patient->last_name = $data->last_name;
        $patient->date_of_birth = $data->date_of_birth;
        $patient->gender = $data->gender;

        $patient->save();

        return $patient;
    }
}
