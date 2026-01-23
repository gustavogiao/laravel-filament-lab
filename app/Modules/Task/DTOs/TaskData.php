<?php

namespace App\Modules\Task\DTOs;

final readonly class TaskData
{
    public function __construct(
        public string $title,
        public ?string $description,
        public bool $is_completed,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            description: $data['description'] ?? null,
            is_completed: $data['is_completed'] ?? false,
        );
    }
}
