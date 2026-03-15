<?php

namespace Database\Factories;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'priority'    => fake()->randomElement(TaskPriorityEnum::values()),
            'status'      => fake()->randomElement(TaskStatusEnum::values()),
            'due_date'    => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'assigned_to' => User::factory(),
            'ai_summary'  => null,
            'ai_priority' => null,
        ];
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes) => [
            'priority' => TaskPriorityEnum::High->value,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TaskStatusEnum::Completed->value,
        ]);
    }
}
