<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    public function test_register_successful()
    {
        Storage::fake('public');

        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com', //unique
            'phone' => '1234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
            'user_image' => UploadedFile::fake()->image('user.jpg'),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
            'name' => 'Test User',
        ]);
    }

    public function test_register_fails_with_invalid_data()
    {
        $response = $this->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
            'role' => 'invalid-role',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password', 'role']);
    }

    public function test_login_successful()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'testuser1@example.com',
            'role' => 'user',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser1@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'token',
                'data' => [
                    'id',
                    'name',
                    'email',
                    'role',
                ],
            ]);

        $this->assertNotNull($user->tokens()->first());
    }

    public function test_login_fails_with_invalid_credentials()
    {
        $user = \App\Models\User::factory()->create([
            'email' => 'testuser2@example.com',
            'password' => Hash::make('password123'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'testuser2@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'success' => false,
                'message' => 'Invalid email or password',
            ]);
    }

    public function test_logout_successful()
    {
        $user = \App\Models\User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $response = $this->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Logged out successfully',
            ]);

        $user->refresh();
        $this->assertCount(0, $user->tokens);

        // Refresh the authentication state
        $this->app['auth']->forgetGuards();

        $this->assertGuest('sanctum');
    }

}
