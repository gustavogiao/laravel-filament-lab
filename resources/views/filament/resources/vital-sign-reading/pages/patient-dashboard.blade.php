<x-filament-panels::page>
    <x-filament::section class="mb-8">
        <x-slot name="heading">
            Patient
        </x-slot>

        <x-slot name="description">
            Select a patient to view detailed vital sign data.
        </x-slot>

        <div class="max-w-xl space-y-3">
            <select
                wire:model.live="patientId"
                id="patientId"
                class="fi-input block w-full rounded-xl"
            >
                <option value="">Select a patient...</option>

                @foreach ($this->getPatients() as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>

            <div wire:loading wire:target="patientId" class="flex items-center gap-2 text-sm text-gray-500">
                <x-filament::loading-indicator class="h-4 w-4" />
                Loading patient data...
            </div>

            @if ($patientId)
                <div wire:loading.remove wire:target="patientId">
                    <x-filament::badge color="success">
                        Patient selected
                    </x-filament::badge>
                </div>
            @endif
        </div>
    </x-filament::section>

    @if ($patientId && $this->patientHasReadings())
        <div wire:loading.remove wire:target="patientId" class="mt-10">
            <x-filament-widgets::widgets
                :widgets="$this->widgets()"
                :columns="$this->getWidgetsColumns()"
            />
        </div>

    @elseif ($patientId)
        <x-filament::section class="mt-10">
            <div class="py-16 text-center">
                <x-filament::icon
                    icon="heroicon-o-exclamation-circle"
                    class="mx-auto h-12 w-12 text-gray-400"
                />

                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
                    No vital sign data
                </h3>

                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    No readings were found for this patient.
                </p>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
