<?php

declare(strict_types=1);

namespace App\Modules\Task\Models;

use App\Models\BaseModel;
use App\Modules\Project\Models\Project;
use App\Modules\Task\Policies\TaskPolicy;
use App\Modules\User\Models\User;
use Carbon\CarbonInterface;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Attributes\UsePolicy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property bool $is_completed
 * @property string $project_id
 * @property string $submitted_by_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface|null $deleted_at
 */
#[UsePolicy(TaskPolicy::class)]
#[UseFactory(TaskFactory::class)]
final class Task extends BaseModel
{
    protected $table = 'tasks';

    protected static function newFactory(): TaskFactory
    {
        return TaskFactory::new();
    }

    /**
     * @var list<string>
     */
    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    public function casts(): array
    {
        return [
            'title' => 'string',
            'description' => 'string',
            'is_completed' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by_id', 'id');
    }
}
