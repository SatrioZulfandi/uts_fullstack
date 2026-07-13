<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\CheckInSuccessMail;
use App\Models\BorrowingSchedule;
use App\Models\Inventory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * API Controller untuk fitur member (Tablet App).
 * Menangani pengambilan data inventaris dan proses check-in.
 */
class MemberController extends Controller
{
    /**
     * GET /api/inventories
     * Mengambil daftar inventaris yang berstatus 'available'.
     */
    public function availableInventories(): JsonResponse
    {
        // Ambil semua inventaris dengan status 'available'
        $inventories = Inventory::where('status', 'available')->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar inventaris yang tersedia.',
            'data' => $inventories,
        ], 200);
    }

    /**
     * GET /api/my-schedules
     * Mengambil daftar jadwal milik member yang login.
     */
    public function schedules(Request $request): JsonResponse
    {
        $query = BorrowingSchedule::with('inventory')
            ->where('user_id', $request->user()->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
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
     * GET /api/my-schedules/{schedule}
     * Mengambil detail jadwal peminjaman milik member yang login.
     */
    public function showSchedule(Request $request, $id): JsonResponse
    {
        $schedule = BorrowingSchedule::with('inventory')->find($id);

        if (! $schedule) {
            return response()->json([
                'status' => false,
                'message' => 'Jadwal tidak ditemukan.',
                'data' => null,
            ], 404);
        }

        if ($schedule->user_id !== $request->user()->id) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke jadwal ini.',
                'data' => null,
            ], 403);
        }

        return response()->json([
            'status' => true,
            'message' => 'Detail jadwal berhasil diambil.',
            'data' => $schedule,
        ], 200);
    }

    /**
     * POST /api/check-in
     * Proses check-in peralatan secara real-time oleh member.
     */
    public function checkIn(Request $request): JsonResponse
    {
        // Validasi input
        $request->validate([
            'schedule_id' => 'required|exists:borrowing_schedules,id',
        ]);

        $response = null;

        // Gunakan database transaction untuk memastikan konsistensi data
        DB::transaction(function () use ($request, &$response) {
            // Ambil jadwal dengan lockForUpdate untuk mencegah race conditions
            $schedule = BorrowingSchedule::with(['inventory', 'user'])
                ->lockForUpdate()
                ->find($request->schedule_id);

            // Validasi kepemilikan
            if ($schedule->user_id !== $request->user()->id) {
                $response = response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke jadwal ini.',
                    'data' => null,
                ], 403);

                return;
            }

            // Validasi status jadwal
            if ($schedule->status !== 'booked') {
                $response = response()->json([
                    'status' => false,
                    'message' => 'Jadwal ini tidak dapat di-check-in. Status saat ini: '.$schedule->status,
                    'data' => null,
                ], 409); // 409 Conflict

                return;
            }

            // Validasi ketersediaan inventory
            $inventory = Inventory::lockForUpdate()->find($schedule->inventory_id);
            if ($inventory->status !== 'available') {
                $response = response()->json([
                    'status' => false,
                    'message' => 'Inventaris tidak tersedia saat ini. Status: '.$inventory->status,
                    'data' => null,
                ], 409);

                return;
            }

            // Ubah status
            $schedule->update(['status' => 'checked_in']);
            $inventory->update(['status' => 'borrowed']);

            // Set the schedule for email logic later
            $request->attributes->set('checked_in_schedule', $schedule);
        });

        if ($response) {
            return $response;
        }

        $schedule = $request->attributes->get('checked_in_schedule');

        // Kirim notifikasi email ke member
        $emailSent = false;
        try {
            Mail::to($schedule->user->email)->send(new CheckInSuccessMail($schedule));
            $emailSent = true;

            Log::info('Check-in email berhasil dikirim.', [
                'user_id' => $schedule->user->id,
                'schedule_id' => $schedule->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email check-in.', [
                'user_id' => $schedule->user->id,
                'schedule_id' => $schedule->id,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Check-in berhasil dilakukan.',
            'email_sent' => $emailSent,
            'data' => [
                'schedule' => $schedule,
                'inventory' => $schedule->inventory,
            ],
        ], 200);
    }
}
