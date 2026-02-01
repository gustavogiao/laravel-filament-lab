<?php

namespace Database\Factories;

use App\Modules\Permissions\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Date;

/**
 * @extends Factory<Role>
 */
final class RoleFactory extends Factory
{

    protected $model = Role::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'created_at' => Date::now(),
            'updated_at' => Date::now(),
            'guard_name' => fake()->name(),
            'name' => fake()->name(),
        ];
    }
}
