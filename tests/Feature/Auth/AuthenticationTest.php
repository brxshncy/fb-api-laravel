<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Authentication', function () {
    it('User can register', function () {
        $userData = [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password', // keep it simple for testing
            'password_confirmation' => 'password',
        ];

        $response = $this->post(route('auth.register'), $userData);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
            ],
            'token'
        ]);

        $this->assertDatabaseHas('users', ['email' => $userData['email']]);
    });

    it('User can login', function () {
            $user = User::factory()->create([
                'password' => bcrypt('password')
            ]);

            $response = $this->post(route('auth.login'), [
                'email' => $user->email, 
                'password' => 'password'
            ]);

            $response->assertStatus(200);
            $response->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'email',
                    'created_at',
                ],
                'token'
            ]);
    });
});