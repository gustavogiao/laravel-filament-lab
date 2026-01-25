<?php

declare(strict_types=1);

namespace App\Modules\Project\DTOs;

final readonly class ProjectData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public bool $isActive,
        public string $owner_id,
    ) {}

    /**
     * Factory helper
     *
     * @param array{
     *   name: string,
     *   description?: string|null,
     *   is_active?: bool,
     *   owner_id: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            isActive: $data['is_active'] ?? true,
            owner_id: $data['owner_id'],
        );
    }
}
