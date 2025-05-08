<?php

<<<<<<< HEAD:tests/Feature/FriendRequest/FrindRequestTest.php
use App\Models\FriendRequest;
=======
use App\Enums\FriendShipStatus;
use App\Models\FriendShip;
>>>>>>> 97dd94173cb461698aa1d92a7ff544f78624615a:tests/Feature/FriendShip/FriendShipTest.php
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();

    FriendRequest::factory()
        ->count(10)
        ->create([
            'user_id' => $this->user->id, // All friend requests go to this user
        ]);
});

describe('Friend Requests', function () {
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

    it ("Logged in user can view all the pending friend requests", function () {
    
            $response = $this->actingAs($this->user)
                            ->getJson(route('friendship.index'));

            dd($response->getContent());

    });
});