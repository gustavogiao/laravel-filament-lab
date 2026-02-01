<?php

namespace App\Modules\Telemetry\Filament\Resources\Devices\Pages;

use App\Modules\Telemetry\Actions\CreateDeviceAction;
use App\Modules\Telemetry\DTOs\DeviceData;
use App\Modules\Telemetry\Filament\Resources\Devices\DeviceResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDevice extends CreateRecord
{
    protected static string $resource = DeviceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return app(CreateDeviceAction::class)
            ->execute(DeviceData::fromArray($data));
    }
}
