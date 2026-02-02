<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Widgets;

use App\Modules\Telemetry\Models\VitalSignReading;
use Illuminate\Support\Collection;

final class BloodPressureChart extends BaseVitalChartWidget
{
    protected ?string $heading = 'Blood Pressure (mmHg)';

    protected static ?int $sort = 2;

    protected function readingsQuery(): Collection
    {
        return VitalSignReading::query()
            ->where('patient_id', $this->patientId)
            ->whereNotNull('blood_pressure_systolic')
            ->whereNotNull('blood_pressure_diastolic')
            ->orderBy('recorded_at')
            ->limit($this->readingsLimit)
            ->get();
    }

    protected function datasets(Collection $readings): array
    {
        return [
            [
                'label' => 'Systolic',
                'data' => $readings->pluck('blood_pressure_systolic')->toArray(),
                'borderColor' => '#3b82f6',
                'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
            ],
            [
                'label' => 'Diastolic',
                'data' => $readings->pluck('blood_pressure_diastolic')->toArray(),
                'borderColor' => '#8b5cf6',
                'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'beginAtZero' => false,
                    'min' => 60,
                    'max' => 180,
                ],
            ],
        ];
    }
}
