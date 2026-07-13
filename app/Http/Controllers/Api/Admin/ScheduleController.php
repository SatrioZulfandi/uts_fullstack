<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;
use App\Models\BorrowingSchedule;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * GET /api/admin/schedules
     * Mengambil daftar jadwal peminjaman.
     */
    public function index(Request $request): JsonResponse
    {
        $query = BorrowingSchedule::with(['user', 'inventory']);

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            })->orWhereHas('inventory', function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%');
            });
        }

        if ($request->has('status') && ! empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('user_id') && ! empty($request->user_id)) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('inventory_id') && ! empty($request->inventory_id)) {
            $query->where('inventory_id', $request->inventory_id);
        }

        if ($request->has('start_date') && ! empty($request->start_date)) {
            $query->whereDate('start_time', '>=', $request->start_date);
        }

        if ($request->has('end_date') && ! empty($request->end_date)) {
            $query->whereDate('end_time', '<=', $request->end_date);
        }

        $schedules = $query->orderBy('start_time', 'desc')->paginate($request->input('per_page', 10));

        return response()->json([
            'status' => true,
            'message' => 'Daftar jadwal peminjaman berhasil diambil.',
            'data' => $schedules->items(),
            'meta' => [
                'current_page' => $schedules->currentPage(),
                'last_page' => $schedules->lastPage(),
                'per_page' => $schedules->perPage(),
                'total' => $schedules->total(),
            ],
            'links' => [
                'first' => $schedules->url(1),
                'last' => $schedules->url($schedules->lastPage()),
                'prev' => $schedules->previousPageUrl(),
                'next' => $schedules->nextPageUrl(),
            ],
        ], 200);
    }

    /**
     * GET /api/admin/schedules/{schedule}
     */
    public function show($id): JsonResponse
    {
        $schedule = BorrowingSchedule::with(['user', 'inventory'])->find($id);

        if (! $schedule) {
            return response()->json([
                'status' => false,
                'message' => 'Jadwal tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail jadwal berhasil diambil.',
            'data' => $schedule,
        ], 200);
    }

    /**
     * POST /api/admin/schedules
     */
    public function store(ScheduleRequest $request): JsonResponse
    {
        // 1. Pastikan user adalah member
        $user = User::find($request->user_id);
        if ($user->role !== 'member') {
            return response()->json([
                'status' => false,
                'message' => 'User yang dipilih bukan member.',
                'errors' => ['user_id' => ['User yang dipilih harus memiliki role member.']],
            ], 422);
        }

        // 2. Cek apakah ada jadwal yang bentrok (overlap)
        // Aturan: status bukan cancelled
        $conflict = BorrowingSchedule::where('inventory_id', $request->inventory_id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($conflict) {
            return response()->json([
                'status' => false,
                'message' => 'Jadwal peminjaman bentrok dengan jadwal yang sudah ada.',
                'errors' => ['start_time' => ['Waktu yang dipilih sudah digunakan.']],
            ], 409);
        }

        // 3. Buat jadwal
        $schedule = BorrowingSchedule::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Jadwal berhasil ditambahkan.',
            'data' => $schedule,
        ], 201);
    }

    /**
     * PATCH /api/admin/schedules/{schedule}
     */
    public function update(ScheduleRequest $request, $id): JsonResponse
    {
        $schedule = BorrowingSchedule::find($id);

        if (! $schedule) {
            return response()->json([
                'status' => false,
                'message' => 'Jadwal tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        // 1. Pastikan user adalah member
        $user = User::find($request->user_id);
        if ($user->role !== 'member') {
            return response()->json([
                'status' => false,
                'message' => 'User yang dipilih bukan member.',
                'errors' => ['user_id' => ['User yang dipilih harus memiliki role member.']],
            ], 422);
        }

        // 2. Cek apakah ada jadwal yang bentrok (mengabaikan schedule ini sendiri)
        $conflict = BorrowingSchedule::where('inventory_id', $request->inventory_id)
            ->where('id', '!=', $id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                    ->where('end_time', '>', $request->start_time);
            })->exists();

        if ($conflict) {
            return response()->json([
                'status' => false,
                'message' => 'Jadwal peminjaman bentrok dengan jadwal yang sudah ada.',
                'errors' => ['start_time' => ['Waktu yang dipilih sudah digunakan.']],
            ], 409);
        }

        // 3. Update jadwal
        $schedule->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Jadwal berhasil diperbarui.',
            'data' => $schedule,
        ], 200);
    }
}
