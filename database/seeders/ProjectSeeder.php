<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Project\Models\Project;
use Illuminate\Database\Seeder;

final class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::factory()->count(20)->create();
    }
}
