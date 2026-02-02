<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Widgets;

use App\Modules\Telemetry\Models\VitalSignReading;
use Illuminate\Support\Collection;

final class HeartRateChart extends BaseVitalChartWidget
{
    protected ?string $heading = 'Heart Rate (bpm)';

    protected static ?int $sort = 1;

    protected function readingsQuery(): Collection
    {
        return VitalSignReading::query()
            ->where('patient_id', $this->patientId)
            ->whereNotNull('heart_rate')
            ->orderBy('recorded_at')
            ->limit($this->readingsLimit)
            ->get();
    }

    protected function datasets(Collection $readings): array
    {
        return [
            [
                'label' => 'BPM',
                'data' => $readings->pluck('heart_rate')->toArray(),
                'borderColor' => '#ef4444',
                'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                'fill' => true,
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
                    'min' => 40,
                    'max' => 140,
                ],
            ],
        ];
    }
}
