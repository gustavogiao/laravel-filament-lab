<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Widgets;

use App\Modules\Telemetry\Models\VitalSignReading;
use Illuminate\Support\Collection;

final class RespiratoryRateChart extends BaseVitalChartWidget
{
    protected ?string $heading = 'Respiratory Rate (rpm)';

    protected static ?int $sort = 3;

    protected function readingsQuery(): Collection
    {
        return VitalSignReading::query()
            ->where('patient_id', $this->patientId)
            ->whereNotNull('respiratory_rate')
            ->orderBy('recorded_at')
            ->limit($this->readingsLimit)
            ->get();
    }

    protected function datasets(Collection $readings): array
    {
        return [
            [
                'label' => 'RPM',
                'data' => $readings->pluck('respiratory_rate')->toArray(),
                'borderColor' => '#10b981',
                'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
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
                    'min' => 10,
                    'max' => 30,
                ],
            ],
        ];
    }
}
