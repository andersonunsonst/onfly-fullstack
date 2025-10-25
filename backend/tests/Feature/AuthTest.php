<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function user_can_login_and_receive_token(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $response->assertOk()
                 ->assertJsonStructure(['access_token', 'token_type']);
    }

    #[Test]
    public function user_cannot_login_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'fake@example.com',
            'password' => 'wrong'
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function user_can_logout_successfully(): void
    {
        $user = User::factory()->create(['password' => bcrypt('secret')]);

        $tokenResponse = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $token = $tokenResponse->json('access_token');

        $response = $this->withHeader('Authorization', "Bearer {$token}")
                         ->postJson('/api/logout');

        $response->assertOk()
                 ->assertJson(['message' => 'Deslogado com sucesso.']);
    }
}
