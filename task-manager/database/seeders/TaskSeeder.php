<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        if ($users->isEmpty()) {
            $this->command->warn('No regular users found. Please run UserSeeder first.');
            return;
        }

        foreach ($users as $user) {
            // Assign 3-6 tasks per user
            Task::factory()
                ->count(random_int(3, 6))
                ->create(['assigned_to' => $user->id]);
        }
    }
}
