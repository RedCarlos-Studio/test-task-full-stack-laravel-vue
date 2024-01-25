<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->sentence(),
            'is_completed' => rand(Task::STATUS_IS_OPEN, Task::STATUS_IS_COMPLETED),
            'is_deleted' => rand(Task::STATUS_IS_NOT_DELETED, Task::STATUS_IS_DELETED)
        ];
    }
}
