<?php

namespace App\Modules\Task\Actions;

use App\Modules\Task\DTOs\TaskData;
use App\Modules\Task\Models\Task;

final class CreateTaskAction
{

    public function execute(TaskData $data): Task
    {
        $task = new Task;

        $task->project_id = $data->project_id;
        $task->title = $data->title;
        $task->description = $data->description;
        $task->is_completed = $data->is_completed;

        $task->save();

        return $task;
    }

}
