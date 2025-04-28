<?php

use App\Enums\FriendShipStatus;
use App\Models\FriendShip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Friendship', function () {
    it("User can send friend request to other user", function () {

        $user = User::factory()->create();
        $friend = User::factory()->create();

        $response = $this->actingAs($user)
                        ->postJson(route('friendship.store'), ['friend_id' => $friend->id]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'user' => ['id', 'name', 'email'],
                'friend' => ['id', 'name', 'email'],
                'status'
            ]
        ]);

        $this->assertDatabaseHas('friend_ships', ['user_id' => $user->id, 'friend_id' => $friend->id]);
    });

    it("Added user can accept friend request", function () {
        $user = User::factory()->create();
        $friend = User::factory()->create();

        $response = $this->actingAs($user)
                         ->postJson(route('friendship.store'), ['friend_id' => $friend->id])
                         ->assertStatus(201);
        
        $friendship = FriendShip::where('user_id' ,$user->id)->where('friend_id', $friend->id)->first();
    
        $response = $this->actingAs($friend)
                ->patchJson(route('friendship.update',  $friendship->id), ['decision' => FriendShipStatus::ACCEPTED]);
        
        dd($response->getContent());
    });
});