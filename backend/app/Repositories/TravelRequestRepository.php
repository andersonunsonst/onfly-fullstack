<?php

namespace App\Repositories;

use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class TravelRequestRepository
{
    /**
     * Cria um novo pedido de viagem.
     */
    public function create(array $data): TravelRequest
    {
        return TravelRequest::create($data);
    }

    /**
     * Encontra um pedido de viagem pelo ID.
     */
    public function find(int $id): ?TravelRequest
    {
        return TravelRequest::with('user')->find($id);
    }

    /**
     * Retorna pedidos de viagem filtrados conforme o tipo de usuário.
     * - Admin vê todos
     * - Usuário comum vê apenas os próprios
     */
    public function filterForUser(User $user, array $filters = []): Collection
    {
        $query = TravelRequest::query();

        // Apenas admins veem todos os registros
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['destination'])) {
            $query->where('destination', 'like', '%' . $filters['destination'] . '%');
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('departure_date', [$filters['start_date'], $filters['end_date']])
                ->orWhereBetween('return_date', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->with('user')->latest()->get();
    }

    /**
     * Atualiza o status de um pedido.
     */
    public function updateStatus(TravelRequest $travel, string $status): TravelRequest
    {
        $travel->update(['status' => $status]);
        return $travel->fresh('user');
    }
}
