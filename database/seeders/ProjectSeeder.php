<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;

final class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $userId = User::first()->id;

        Project::factory()
            ->count(20)
            ->create(['owner_id' => $userId]);
    }
}
