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
        $ext = ['jpg', 'png', 'pdf', 'docx', 'xlsx', 'pptx', 'txt', 'zip', 'rar', 'mp3', 'mp4'];
        return [
            'uuid' => $this->faker->uuid,
            'display_name' => $this->faker->word . '.' . rand(0, 1) ? $ext[rand(0, count($ext) - 1)] : 'txt',
            'hash_name' => $this->faker->word,
            'path' => $this->faker->word,
            'mime_type' => $this->faker->word,
            'size' => $this->faker->randomNumber(),
            'task_id' => \App\Models\Task::factory(),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
