<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Actions;

use App\Modules\Telemetry\Models\Device;
use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\VitalSignReading;

final class RecordVitalSignsAction
{
    public function execute(Device $device, array $data): VitalSignReading
    {
        $patient = $device->activePatient();

        if (! $patient instanceof Patient) {
            abort(422, 'Device not assigned to any active patient');
        }

        /** @var VitalSignReading $reading */
        $reading = $patient->vitalSignReadings()->create([
            ...$data,
            'device_id' => $device->id,
            'recorded_at' => $data['recorded_at'] ?? now(),
        ]);

        return $reading;
    }
}
