<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\DTOs;

final readonly class DeviceData
{
    public function __construct(
        public string $device_uid,
    ) {}

    /**
     * Factory helper
     *
     * @param array{
     *   device_uid: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            device_uid: $data['device_uid'],
        );
    }
}
