<?php

use App\Enums\PostPrivacy;
use App\Models\Friend;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user =  User::factory()->create();
    $this->friend = User::factory()->create();
    $this->createFriendShip = Friend::create(['user_id' => $this->user->id, 'friend_id' => $this->friend->id]);
    $this->post = Post::factory()->create();
    $this->postData = [
        'content' => fake()->realTextBetween(100, 300),
        'image_url' => fake()->imageUrl(),
        'privacy' => PostPrivacy::PUBLIC,
    ];
});

describe('Post CRUD', function () {
    it ('User can create a facebook post', function () {

       $response = $this->actingAs($this->user)->postJson(route('post.store'), $this->postData);

       $response->assertStatus(201);
       $response->assertJsonStructure([
            'data' => [
                'id',
                'content',
                'image_url',
                'created_at',
                'updated_at',
                'privacy',
                'user'
            ]
        ]);

        $this->assertDatabaseHas('posts', [
            'content' => $this->postData['content'],
            'image_url' => $this->postData['image_url'],
            'privacy' => $this->postData['privacy'],
        ]);
    });

    it ("User can  see friend's post", function () {
        $response = $this->actingAs($this->user)
                          ->getJson(route('post.index'));
        
        dd($response->getContent());
    });
});