<?php

namespace App\Modules\Task\Actions;

use App\Modules\Task\DTOs\TaskData;
use App\Modules\Task\Models\Task;

final readonly class UpdateTaskAction
{
    public function execute(Task $task, TaskData $data): Task
    {
        $task->title = $data->title;
        $task->description = $data->description;
        $task->is_completed = $data->is_completed;

        $task->save();

        return $task;
    }
}
