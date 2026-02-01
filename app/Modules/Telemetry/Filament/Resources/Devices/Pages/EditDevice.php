<?php

namespace App\Modules\Telemetry\Filament\Resources\Devices\Pages;

use App\Modules\Shared\Filament\Concerns\RedirectsToIndex;
use App\Modules\Telemetry\Actions\UpdateDeviceAction;
use App\Modules\Telemetry\DTOs\DeviceData;
use App\Modules\Telemetry\Filament\Resources\Devices\DeviceResource;
use App\Modules\Telemetry\Models\Device;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditDevice extends EditRecord
{
    use RedirectsToIndex;

    protected static string $resource = DeviceResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Device
    {
        /** @var Device $record */
        return app(UpdateDeviceAction::class)
            ->execute($record, DeviceData::fromArray($data));
    }
}
