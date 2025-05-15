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
    $this->post = Post::factory()->create(['user_id' => $this->user->id]);
    $this->postData = [
        'content' => fake()->realTextBetween(100, 300),
        'image_url' => fake()->imageUrl(),
        'privacy' => PostPrivacy::PUBLIC,
    ];

    $this->updateData = [
        'content' => 'Updated the post content',
        'image_url' => fake()->imageUrl(), 
        'privacy' => PostPrivacy::FRIENDS 
    ];
});

describe('Logged in user ', function () {
    it ('can create a facebook post', function () {

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

        $this->assertDatabaseHas('posts',$this->postData);
    });

    it ("can see their own and their friend's post only", function () {
        $friends = User::factory()->count(10)->create();
        $visiblePostIds = [];

        foreach ($friends as $friend) {
            Friend::create([
                'user_id' => $this->user->id,
                'friend_id' => $friend->id
            ]);
            $post = Post::factory()->for($friend)->create(['content' => "Post {$friend->id}"]);
            $visiblePostIds[] = $post->id;
        }
        $response = $this->actingAs($this->user)->getJson(route('post.index'));
        $response->assertStatus(200);

        $posts = collect($response->json('data'));
   
        foreach ($visiblePostIds as $expectedId) {
            expect($posts->pluck('id'))->toContain($expectedId);
        }

    });

    it ("can view single post details", function () {
        $response = $this->actingAs($this->user)->getJson(route('post.show',  $this->post));

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $this->post->id,
                'content' => $this->post->content,
                'image_url' =>$this->post->image_url,
                'privacy' => PostPrivacy::PUBLIC->value,
            ]
        ]);

    });

    it ("can update their own post", function () {
      
        $response = $this->actingAs($this->user)->putJson(
            route('post.update', $this->post),
            [
                'content' =>  $this->updateData['content'],
                'image_url' => $this->updateData['image_url'],
                'privacy' => $this->updateData['privacy']
            ]
        );

        $response->assertStatus(200);
        $response->assertJson([
            'data' => [
                'id' => $this->post->id,
                'content' => $this->updateData['content'],
                'image_url' => $this->updateData['image_url'],
                'privacy' => PostPrivacy::FRIENDS->value,
            ]
        ]);

        $this->assertDatabaseHas('posts', array_merge(['id' => $this->post->id], $this->updateData));

    });


    it ("cannot update post when it's not owned by login user", function () {
        $response = $this->actingAs($this->friend)
                         ->putJson(route('post.update', $this->post),[
                'content' => $this->updateData['content'],
                'image_url' =>  $this->updateData['image_url'],
                'privacy' =>  $this->updateData['privacy']
        ]);
         $response->assertStatus(403);
    });
});