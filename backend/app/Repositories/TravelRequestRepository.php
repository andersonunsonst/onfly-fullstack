<?php

namespace App\Repositories;

use App\Models\TravelRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TravelRequestRepository
{
    public function create(array $data): TravelRequest
    {
        return TravelRequest::create($data);
    }

    public function find(int $id): ?TravelRequest
    {
        return TravelRequest::find($id);
    }

    public function filter(array $filters): Collection
    {
        $query = TravelRequest::query()->where('user_id', Auth::id());

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['destination'])) {
            $query->where('destination', 'like', '%' . $filters['destination'] . '%');
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $query->whereBetween('departure_date', [$filters['start_date'], $filters['end_date']]);
        }

        return $query->with('user')->latest()->get();
    }

    public function updateStatus(TravelRequest $travel, string $status): TravelRequest
    {
        $travel->update(['status' => $status]);
        return $travel;
    }
}
