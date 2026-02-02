<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Traits;

use Livewire\Attributes\On;

trait InteractsWithPatient
{
    public ?string $patientId = null;

    #[On('patientUpdated')]
    public function updatePatientId(?string $patientId): void
    {
        if ($this->patientId !== $patientId) {
            $this->patientId = $patientId;
        }
    }

    protected function hasPatient(): bool
    {
        return filled($this->patientId);
    }

    protected function emptyChartData(): array
    {
        return [
            'datasets' => [],
            'labels' => [],
        ];
    }
}
