<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TravelRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class TravelRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create(['role' => 'user']);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    #[Test]
    public function user_can_create_a_travel_request(): void
    {
        $payload = [
            'destination' => 'São Paulo',
            'departure_date' => '2025-11-10',
            'return_date' => '2025-11-15',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/travel-requests', $payload);

        $response->assertCreated()
                 ->assertJsonPath('data.destination', 'São Paulo');

        $this->assertDatabaseHas('travel_requests', [
            'user_id' => $this->user->id,
            'destination' => 'São Paulo',
        ]);
    }

    #[Test]
    public function user_cannot_set_return_date_before_departure(): void
    {
        $payload = [
            'destination' => 'Rio de Janeiro',
            'departure_date' => '2025-11-20',
            'return_date' => '2025-11-15',
        ];

        $response = $this->actingAs($this->user, 'sanctum')
                         ->postJson('/api/travel-requests', $payload);

        $response->assertStatus(422);
    }

    #[Test]
    public function admin_can_list_all_travel_requests(): void
    {
        TravelRequest::factory()->count(3)->create();

        $response = $this->actingAs($this->admin, 'sanctum')
                         ->getJson('/api/travel-requests');

        $response->assertOk();
        $this->assertCount(3, $response->json('data'));
    }

    #[Test]
    public function user_can_list_only_their_own_travel_requests(): void
    {
        TravelRequest::factory()->create(['user_id' => $this->user->id]);
        TravelRequest::factory()->create(['user_id' => $this->admin->id]);

        $response = $this->actingAs($this->user, 'sanctum')
                         ->getJson('/api/travel-requests');

        $response->assertOk();
        $this->assertCount(1, $response->json('data'));
    }

    #[Test]
    public function only_admin_can_update_travel_status(): void
    {
        $travel = TravelRequest::factory()->create([
            'user_id' => $this->user->id,
            'status' => 'requested',
        ]);

        // Tentativa com usuário comum
        $responseUser = $this->actingAs($this->user, 'sanctum')
                             ->patchJson("/api/travel-requests/{$travel->id}/status", [
                                 'status' => 'approved'
                             ]);

        $responseUser->assertStatus(403);

        // Tentativa com admin (válida)
        $responseAdmin = $this->actingAs($this->admin, 'sanctum')
                              ->patchJson("/api/travel-requests/{$travel->id}/status", [
                                  'status' => 'approved'
                              ]);

        $responseAdmin->assertOk()
                      ->assertJsonPath('data.status', 'approved');
    }
}
