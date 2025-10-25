<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TravelRequestService;
use App\Repositories\TravelRequestRepository;

class TravelRequestController extends Controller
{
    private TravelRequestService $service;
    private TravelRequestRepository $repository;

    public function __construct(
        TravelRequestService $service,
        TravelRequestRepository $repository
    ) {
        $this->service = $service;
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'destination', 'start_date', 'end_date']);
        return response()->json($this->repository->filter($filters));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'destination' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'return_date' => 'required|date|after_or_equal:departure_date',
        ]);

        $travel = $this->service->create($validated);

        return response()->json([
            'message' => 'Travel request created successfully!',
            'data' => $travel
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:approved,canceled',
        ]);

        $result = $this->service->updateStatus($request->user(), $id, $validated['status']);

        return response()->json($result);
    }
}