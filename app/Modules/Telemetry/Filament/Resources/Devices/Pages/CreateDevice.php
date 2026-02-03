<?php

namespace App\Modules\Telemetry\Filament\Resources\Devices\Pages;

use App\Modules\Telemetry\Actions\CreateDeviceAction;
use App\Modules\Telemetry\DTOs\DeviceData;
use App\Modules\Telemetry\Filament\Resources\Devices\DeviceResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateDevice extends CreateRecord
{
    protected static string $resource = DeviceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $device = app(CreateDeviceAction::class)
            ->execute(DeviceData::fromArray($data));

        if ($plainToken = $device->getAttribute('plain_api_token')) {
            Notification::make()
                ->success()
                ->title('Device created successfully!')
                ->body("API Token (save this now, it won't be shown again):\n\n`{$plainToken}`")
                ->persistent()
                ->send();
        }

        return $device;
    }
}
