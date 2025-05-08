<?php

use App\Models\FriendRequest;
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
                        ->postJson(route('friend-request.store'), ['friend_id' => $friend->id]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'user' => ['id', 'name', 'email'],
                'friendRequestSentTo' => ['id', 'name', 'email'],
                'status'
            ]
        ]);

        $this->assertDatabaseHas('friend_requests', ['user_id' => $user->id, 'friend_id' => $friend->id]);
    });

    it ("Logged in user can view all the pending friend requests", function () {
    
            $response = $this->actingAs($this->user)
                            ->getJson(route('friend-request.index'));

            dd($response->getContent());

    });

    it ("When user accepts friend request they became friends", function () {
            
    });
});