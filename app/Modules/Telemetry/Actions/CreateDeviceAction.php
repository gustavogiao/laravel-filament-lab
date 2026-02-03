<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\Actions;

use App\Modules\Telemetry\DTOs\DeviceData;
use App\Modules\Telemetry\Models\Device;
use Illuminate\Support\Str;

final class CreateDeviceAction
{
    public function execute(DeviceData $data): Device
    {
        $device = new Device;

        $apiToken = Str::random(64);

        $device->device_uid = $data->device_uid;
        $device->api_token_hash = hash('sha256', $apiToken);

        $device->save();

        $device->setAttribute('plain_api_token', $apiToken);

        return $device;
    }
}
