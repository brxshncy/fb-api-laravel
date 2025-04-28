<?php

namespace Database\Factories;

use App\Enums\FriendShipStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FriendShip>
 */
class FriendShipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        // Ensure the friendship is not created between the same user
        while ($user->id === $friend->id) {
            $friend = User::factory()->create();
        }

        return [
            'user_id' => $user->id,
            'friend_id' => $friend->id,
            'status' =>  FriendShipStatus::PENDING
        ];
    
    }
}
