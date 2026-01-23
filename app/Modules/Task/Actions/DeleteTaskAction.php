<?php

namespace App\Modules\Task\Actions;

use App\Modules\Task\Models\Task;

final readonly class DeleteTaskAction
{
    public function execute(Task $task): void
    {
        $task->delete();
    }
}
