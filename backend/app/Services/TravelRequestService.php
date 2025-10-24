<?php

namespace App\Services;

use App\Models\User;
use App\Models\TravelRequest;
use App\Repositories\TravelRequestRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class TravelRequestService
{
    public function __construct(private TravelRequestRepository $repository) {}

    /**
     * Create a new travel request.
     */
    public function create(array $data): TravelRequest
    {
        $user = Auth::user();

        $payload = [
            'user_id' => $user->id,
            'requester_name' => $user->name,
            'destination' => $data['destination'],
            'departure_date' => $data['departure_date'],
            'return_date' => $data['return_date'],
            'status' => 'requested',
        ];

        return $this->repository->create($payload);
    }

    /**
     * Update travel request status with business rules.
     */
    public function updateStatus(User $user, int $id, string $status): array
    {
        if ($user->role !== 'admin') {
            throw ValidationException::withMessages([
                'permission' => ['Only admins can change status.']
            ]);
        }

        $travel = $this->repository->find($id);
        if (! $travel) {
            throw ValidationException::withMessages(['travel' => ['Travel request not found.']]);
        }

        if ($status === 'canceled' && $travel->status === 'approved') {
            throw ValidationException::withMessages([
                'status' => ['Approved requests cannot be canceled.']
            ]);
        }

        $travel = $this->repository->updateStatus($travel, $status);

        // Apenas retorna a mensagem como "notificação"
        $message = match ($status) {
            'approved' => 'Travel request approved successfully!',
            'canceled' => 'Travel request canceled successfully!',
            default => 'Status updated successfully!',
        };

        return [
            'message' => $message,
            'data' => $travel
        ];
    }
}
