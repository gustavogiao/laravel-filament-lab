<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
final class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->optional()->paragraph(),
            'is_completed' => $this->faker->boolean(30),
            'submitted_by_id' => User::inRandomOrder()->first()->id,
        ];
    }
}
