<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Actions;

use App\Modules\Telemetry\Models\Device;
use App\Modules\Telemetry\Models\VitalSignReading;

final class RecordVitalSignsAction
{
    public function execute(Device $device, array $data): VitalSignReading
    {
        $patient = $device->activePatient();

        abort_if(! $patient, 422, 'Device not assigned to any active patient');

        return $patient->vitalSignReadings()->create([
            ...$data,
            'device_id' => $device->id,
            'recorded_at' => $data['recorded_at'] ?? now(),
        ]);
    }
}
