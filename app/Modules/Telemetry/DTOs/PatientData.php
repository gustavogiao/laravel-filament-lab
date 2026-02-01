<?php

declare(strict_types=1);

namespace App\Modules\Telemetry\DTOs;

final readonly class PatientData
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $date_of_birth,
        public string $gender,
    ) {}

    /**
     * Factory helper
     *
     * @param array{
     *   first_name: string,
     *   last_name: string,
     *   date_of_birth: string,
     *   gender: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            date_of_birth: $data['date_of_birth'],
            gender: $data['gender'],
        );
    }
}
