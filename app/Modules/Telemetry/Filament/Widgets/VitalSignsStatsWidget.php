<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Widgets;

use App\Modules\Telemetry\Models\VitalSignReading;
use App\Modules\Telemetry\Traits\InteractsWithPatient;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class VitalSignsStatsWidget extends BaseWidget
{
    use InteractsWithPatient;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        if (! $this->hasPatient()) {
            return [
                Stat::make('Heart Rate', '-')
                    ->description('Select a patient')
                    ->color('gray'),
                Stat::make('Blood Pressure', '-')
                    ->description('Select a patient')
                    ->color('gray'),
                Stat::make('Body Temperature', '-')
                    ->description('Select a patient')
                    ->color('gray'),
                Stat::make('Respiratory Rate', '-')
                    ->description('Select a patient')
                    ->color('gray'),
            ];
        }

        $latest = VitalSignReading::query()
            ->where('patient_id', $this->patientId)
            ->orderBy('recorded_at', 'desc')
            ->first();

        $previous = VitalSignReading::query()
            ->where('patient_id', $this->patientId)
            ->where('id', '!=', $latest?->id)
            ->orderBy('recorded_at', 'desc')
            ->first();

        if (! $latest) {
            return [
                Stat::make('No Vital Signals', '-')
                    ->description('There is no vital sign data for this patient.')
                    ->icon(Heroicon::ExclamationTriangle)
                    ->color('gray'),
            ];
        }

        $stats = [];

        if ($latest->heart_rate) {
            $hrChange = $previous && $previous->heart_rate
                ? (($latest->heart_rate - $previous->heart_rate) / $previous->heart_rate) * 100
                : 0;

            $hrColor = $latest->heart_rate >= 60 && $latest->heart_rate <= 100 ? 'success' : 'danger';

            $stats[] = Stat::make('Heart Rate', $latest->heart_rate.' bpm')
                ->description($latest->recorded_at->diffForHumans())
                ->descriptionIcon($hrChange > 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down')
                ->color($hrColor)
                ->chart($this->getSparklineData('heart_rate'));
        }

        if ($latest->blood_pressure_systolic && $latest->blood_pressure_diastolic) {
            $bpValue = $latest->blood_pressure_systolic.'/'.$latest->blood_pressure_diastolic;
            $bpColor = $latest->blood_pressure_systolic <= 120 && $latest->blood_pressure_diastolic <= 80
                ? 'success'
                : 'warning';

            $stats[] = Stat::make('Blood Pressure', $bpValue.' mmHg')
                ->description($latest->recorded_at->diffForHumans())
                ->color($bpColor)
                ->chart($this->getSparklineData('blood_pressure_systolic'));
        }

        if ($latest->body_temperature) {
            $tempColor = $latest->body_temperature >= 36.1 && $latest->body_temperature <= 37.2
                ? 'success'
                : 'danger';

            $stats[] = Stat::make('Body Temperature', $latest->body_temperature.' °C')
                ->description($latest->recorded_at->diffForHumans())
                ->color($tempColor)
                ->chart($this->getSparklineData('body_temperature'));
        }

        // Respiratory Rate
        if ($latest->respiratory_rate) {
            $rrColor = $latest->respiratory_rate >= 12 && $latest->respiratory_rate <= 20
                ? 'success'
                : 'warning';

            $stats[] = Stat::make('Respiratory Rate', $latest->respiratory_rate.' rpm')
                ->description($latest->recorded_at->diffForHumans())
                ->color($rrColor)
                ->chart($this->getSparklineData('respiratory_rate'));
        }

        return $stats;
    }

    private function getSparklineData(string $column): array
    {
        if (! $this->patientId) {
            return [];
        }

        return VitalSignReading::query()
            ->where('patient_id', $this->patientId)
            ->whereNotNull($column)
            ->orderBy('recorded_at', 'desc')
            ->limit(7)
            ->pluck($column)
            ->reverse()
            ->toArray();
    }
}
