<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Widgets;

use App\Modules\Telemetry\Traits\InteractsWithPatient;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

abstract class BaseVitalChartWidget extends ChartWidget
{
    use InteractsWithPatient;

    protected int $readingsLimit = 20;

    protected int $cacheTtl = 60;

    abstract protected function readingsQuery(): Collection;

    abstract protected function datasets(Collection $readings): array;

    protected function getData(): array
    {
        if (! $this->hasPatient()) {
            return $this->emptyChartData();
        }

        $readings = $this->cachedReadings();

        if ($readings->isEmpty()) {
            return $this->emptyChartData();
        }

        return [
            'datasets' => $this->datasets($readings),
            'labels' => $this->labels($readings),
        ];
    }

    protected function cachedReadings(): Collection
    {
        return Cache::remember(
            $this->cacheKey(),
            $this->cacheTtl,
            fn () => $this->readingsQuery()
        );
    }

    protected function cacheKey(): string
    {
        return sprintf(
            'vitals:%s:%s',
            $this->cachePrefix(),
            $this->patientId
        );
    }

    protected function cachePrefix(): string
    {
        return str(class_basename($this))
            ->kebab()
            ->toString();
    }

    protected function labels(Collection $readings): array
    {
        return $readings
            ->pluck('recorded_at')
            ->map(fn ($date) => $date->format('H:i'))
            ->toArray();
    }

    protected function emptyChartData(): array
    {
        return [
            'datasets' => [],
            'labels' => [],
        ];
    }
}
