<?php

namespace Database\Seeders;

use App\Modules\Task\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Modules\Project\Models\Project;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projects = Project::all();

        foreach ($projects as $project) {
            Task::factory()
                ->count(10)
                ->for($project)
                ->create();
        }
    }
}
