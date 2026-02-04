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
     *   status?: ProjectStatus,
     *   owner_id: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        $status = $data['status'] ?? ProjectStatus::from('in_progress');

        return new self(
            name: $data['name'],
            description: $data['description'] ?? null,
            status: $status,
            owner_id: $data['owner_id'],
        );
    }
}
