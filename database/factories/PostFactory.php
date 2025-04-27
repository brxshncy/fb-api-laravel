<?php

namespace Database\Factories;

use App\Enums\PostPrivacy;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'content' => fake()->realTextBetween(100, 300),
            'image_url' => fake()->imageUrl(), 
            'privacy' =>  PostPrivacy::PUBLIC
        ];
    }
}
