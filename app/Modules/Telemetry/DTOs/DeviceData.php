<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\DTOs;

final readonly class DeviceData
{
    public function __construct(
        public string $device_uid,
        public string $api_token_hash,
    ) {}

    /**
     * Factory helper
     *
     * @param array{
     *   device_uid: string,
     *   api_token_hash: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            device_uid: $data['device_uid'],
            api_token_hash: $data['api_token_hash'],
        );
    }
}
