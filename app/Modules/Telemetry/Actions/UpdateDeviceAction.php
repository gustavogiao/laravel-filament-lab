<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Actions;

use App\Modules\Telemetry\DTOs\DeviceData;
use App\Modules\Telemetry\Models\Device;

final class UpdateDeviceAction
{
    public function execute(Device $device, DeviceData $data): Device
    {
        $device->device_uid = $data->device_uid;
        $device->api_token_hash = $data->api_token_hash;

        $device->save();

        return $device;
    }
}
