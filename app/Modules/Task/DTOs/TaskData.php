<?php

namespace App\Modules\Task\DTOs;

final readonly class TaskData
{
    public function __construct(
        public string $project_id,
        public string $title,
        public ?string $description,
        public bool $is_completed,
        public string $submitted_by_id,
    ) {}

    /**
     * Factory helper
     *
     * @param array{
     *   project_id: string,
     *   title: string,
     *   description?: string|null,
     *   is_completed?: bool,
     *   submitted_by_id: string,
     * } $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            project_id: $data['project_id'],
            title: $data['title'],
            description: $data['description'] ?? null,
            is_completed: $data['is_completed'] ?? false,
            submitted_by_id: $data['submitted_by_id'],
        );
    }
}
