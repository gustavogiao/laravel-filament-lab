<?php

declare(strict_types=1);

namespace App\Modules\Task\Models;

use App\Modules\Project\Models\Project;
use Carbon\CarbonInterface;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\BaseModel;

/**
 * @property string $id
 * @property string $title
 * @property string|null $description
 * @property bool $is_completed
 * @property string $project_id
 * @property-read CarbonInterface $created_at
 * @property-read CarbonInterface $updated_at
 * @property-read CarbonInterface|null $deleted_at
 */
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
}
