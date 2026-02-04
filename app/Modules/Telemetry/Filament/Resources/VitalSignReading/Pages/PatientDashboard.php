<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Filament\Resources\VitalSignReading\Pages;

use App\Modules\Telemetry\Filament\Resources\VitalSignReading\VitalSignReadingResource;
use App\Modules\Telemetry\Filament\Widgets\BloodPressureChart;
use App\Modules\Telemetry\Filament\Widgets\BodyTemperatureChart;
use App\Modules\Telemetry\Filament\Widgets\HeartRateChart;
use App\Modules\Telemetry\Filament\Widgets\LatestReadingsTable;
use App\Modules\Telemetry\Filament\Widgets\RespiratoryRateChart;
use App\Modules\Telemetry\Filament\Widgets\VitalSignsStatsWidget;
use App\Modules\Telemetry\Models\Patient;
use App\Modules\Telemetry\Models\VitalSignReading;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;

final class PatientDashboard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = VitalSignReadingResource::class;

    protected string $view = 'filament.resources.vital-sign-reading.pages.patient-dashboard';

    protected static ?string $title = 'Vital Signal Readings - Dashboard';

    protected static ?string $navigationLabel = 'Dashboard';

    public ?string $patientId = null;

    protected ?bool $patientHasData = null;

    protected ?array $patients = null;

    protected function getFormSchema(): array
    {
        return [
            Select::make('patientId')
                ->label('Select a patient')
                ->options(fn () => $this->getPatients())
                ->searchable()
                ->placeholder('Select a patient...')
                ->live()
                ->native(false),
        ];
    }

    public function updatedPatientId(?string $value): void
    {
        $this->patientHasData = null;
        $this->dispatch('patientUpdated', patientId: $value);
    }

    protected function patientHasReadings(): bool
    {
        if (! $this->patientId) {
            return false;
        }

        return $this->patientHasData ??= VitalSignReading::query()
            ->where('patient_id', $this->patientId)
            ->exists();
    }

    public function getPatients(): array
    {
        return $this->patients ??= Patient::query()
            ->orderBy('first_name')
            ->get()
            ->mapWithKeys(fn (Patient $p) => [
                $p->id => "{$p->first_name} {$p->last_name}",
            ])
            ->toArray();
    }

    protected function widgets(): array
    {
        return [
            VitalSignsStatsWidget::class,
            HeartRateChart::class,
            BloodPressureChart::class,
            RespiratoryRateChart::class,
            BodyTemperatureChart::class,
            LatestReadingsTable::class,
        ];
    }

    public function getWidgetsColumns(): array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'md' => 2,
            'lg' => 4,
            'xl' => 4,
        ];
    }
}
