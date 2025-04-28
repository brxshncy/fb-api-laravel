<?php

use App\Enums\PostPrivacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Post CRUD', function () {
    it ('Authenticated user can create a facebook post', function () {
        $user = User::factory()->create();

        $postData = [
            'content' => fake()->realTextBetween(100, 300),
            'image_url' => fake()->imageUrl(),
            'privacy' => PostPrivacy::PUBLIC,
        ];

        $response = $this->actingAs($user)->postJson(route('post.store'), $postData);

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
            'content' => $postData['content'],
            'image_url' => $postData['image_url'],
            'privacy' => $postData['privacy'],
        ]);
    });
});