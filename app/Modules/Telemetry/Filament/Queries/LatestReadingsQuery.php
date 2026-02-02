<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Queries;

use App\Modules\Telemetry\Models\VitalSignReading;
use Illuminate\Database\Eloquent\Builder;

final class LatestReadingsQuery
{
    public static function forPatient(?string $patientId, int $limit = 10): Builder
    {
        if (! $patientId) {
            return VitalSignReading::query()->whereRaw('1 = 0');
        }

        return VitalSignReading::query()
            ->where('patient_id', $patientId)
            ->with('device')
            ->orderByDesc('recorded_at')
            ->limit($limit);
    }
}
