<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attachment>
 */
class AttachmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'display_name' => $this->faker->word,
            'hash_name' => $this->faker->word,
            'path' => $this->faker->word,
            'mime_type' => $this->faker->word,
            'size' => $this->faker->randomNumber(),
            'task_id' => \App\Models\Task::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
