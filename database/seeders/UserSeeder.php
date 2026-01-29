<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        User::factory()->count(10)->create();
    }
}
