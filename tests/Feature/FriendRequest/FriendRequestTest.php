<?php

use App\Enums\FriendRequestStatus;
use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->friend =  User::factory()->create();
    $this->friendRequest =FriendRequest::factory()
        ->count(10)
        ->create([
            'user_id' => $this->user->id, // All friend requests go to this user
        ]);
});

describe('Friend Requests', function () {
    it("User can send friend request to other user", function () {

        $response = $this->actingAs($this->user)
                        ->postJson(route('friend-request.store'), ['friend_id' => $this->friend->id]);

        $response->assertStatus(201);

        $response->assertJsonStructure([
            'data' => [
                'id',
                'user' => ['id', 'name', 'email'],
                'friendRequestSentTo' => ['id', 'name', 'email'],
                'status'
            ]
        ]);

        $this->assertDatabaseHas('friend_requests', 
            ['user_id' => $this->user->id, 'friend_id' => $this->friend->id]
        );
    });

    it ("Logged in user can view all the pending friend requests", function () {
            $response = $this->actingAs($this->user)
                            ->getJson(route('friend-request.index'));

            $response->assertStatus(200);
            $response->assertJsonStructure([
                'data' => [[
                    'id',
                    'friendRequestSentTo' => ['name', 'email'],
                    'status'
                ]]
            ]);
    });

    it ("When user accepts friend request they became friends", function () {
            $response = $this->actingAs($this->user)
                              ->putJson(route('friend-request.update',  $this->friendRequest[0]), [
                                    'decision' => FriendRequestStatus::ACCEPTED
                              ]);

           $response->assertStatus(200);
           $response->assertJsonStructure([
                'data' => ['id', 'friendRequestSentTo' => ['name', 'email']]
           ]);

           $this->assertDatabaseHas('friends', ['user_id' => $this->user->id, 'friend_id' => $this->friendRequest[0]->friend_id]);

    });
});