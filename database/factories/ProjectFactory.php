<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
final class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->sentence(3),
            'description' => $this->faker->optional()->paragraph(),
            'is_active' => $this->faker->boolean(85),
            'owner_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
