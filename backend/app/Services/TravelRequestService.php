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


    public function updateStatus(User $user, int $id, string $status): array
    {
        if ($user->role !== 'admin') {
            throw ValidationException::withMessages([
                'permission' => ['Somente usuários Administradores podem alterar status.']
            ]);
        }

        $travel = $this->repository->find($id);
        if (! $travel) {
            throw ValidationException::withMessages(['travel' => ['Viagem não encontrada.']]);
        }

        if ($status === 'canceled' && $travel->status === 'approved') {
            throw ValidationException::withMessages([
                'status' => ['Pedidos aprovados não podem ser cancelados.']
            ]);
        }

        $travel = $this->repository->updateStatus($travel, $status);

        $message = match ($status) {
            'approved' => 'Viagem aprovada com sucesso!',
            'canceled' => 'Viagem cancelada com sucesso!',
            default => 'Status da viagem atualizado com sucesso!',
        };

        return [
            'message' => $message,
            'data' => $travel
        ];
    }
}
