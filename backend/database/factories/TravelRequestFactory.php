<?php

namespace Database\Factories;

use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelRequestFactory extends Factory
{
    protected $model = TravelRequest::class;

    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'requester_name' => $user->name,
            'destination' => $this->faker->city(),
            'departure_date' => $this->faker->dateTimeBetween('+1 week', '+2 weeks')->format('Y-m-d'),
            'return_date' => $this->faker->dateTimeBetween('+3 weeks', '+4 weeks')->format('Y-m-d'),
            'status' => 'requested',
        ];
    }
}
