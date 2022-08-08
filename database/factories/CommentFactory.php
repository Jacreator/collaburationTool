<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'body' => [
                'text' => $this->faker->paragraph,
                'image' => $this->faker->imageUrl(),
            ],
            'post_id' => 1,
            'user_id' => 1,
        ];
    }
}
