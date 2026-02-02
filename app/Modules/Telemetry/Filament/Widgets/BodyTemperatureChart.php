<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Widgets;

use App\Modules\Telemetry\Models\VitalSignReading;
use Illuminate\Support\Collection;

final class BodyTemperatureChart extends BaseVitalChartWidget
{
    protected ?string $heading = 'Body Temperature (°C)';

    protected static ?int $sort = 4;

    protected function readingsQuery(): Collection
    {
        return VitalSignReading::query()
            ->where('patient_id', $this->patientId)
            ->whereNotNull('body_temperature')
            ->orderBy('recorded_at')
            ->limit($this->readingsLimit)
            ->get();
    }

    protected function datasets(Collection $readings): array
    {
        return [
            [
                'label' => '°C',
                'data' => $readings->pluck('body_temperature')->toArray(),
                'borderColor' => '#f59e0b',
                'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
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
                    'min' => 35,
                    'max' => 40,
                ],
            ],
        ];
    }
}
