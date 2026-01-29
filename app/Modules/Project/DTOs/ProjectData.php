<?php

declare(strict_types=1);

namespace App\Modules\Project\DTOs;

use App\Modules\Project\Enums\ProjectStatus;

final readonly class ProjectData
{
    public function __construct(
        public string $name,
        public ?string $description,
        public ProjectStatus $status,
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
            status: $data['status'],
            owner_id: $data['owner_id'],
        );
    }
}
