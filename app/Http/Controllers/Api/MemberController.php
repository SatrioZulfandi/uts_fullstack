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
     *
     * @return JsonResponse
     */
    public function availableInventories(): JsonResponse
    {
        // Ambil semua inventaris dengan status 'available'
        $inventories = Inventory::where('status', 'available')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar inventaris yang tersedia.',
            'data'    => $inventories,
        ], 200);
    }

    /**
     * POST /api/check-in
     * Proses check-in peralatan secara real-time oleh member.
     *
     * Logic:
     * 1. Validasi schedule_id dari payload.
     * 2. Pastikan jadwal milik user yang sedang login.
     * 3. Pastikan status jadwal masih 'booked' (belum di-check-in).
     * 4. Ubah status borrowing_schedule menjadi 'checked_in'.
     * 5. Ubah status inventory menjadi 'borrowed'.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function checkIn(Request $request): JsonResponse
    {
        // Validasi input
        $request->validate([
            'schedule_id' => 'required|exists:borrowing_schedules,id',
        ]);

        // Cari jadwal peminjaman berdasarkan ID
        $schedule = BorrowingSchedule::with('inventory')->find($request->schedule_id);

        // Validasi kepemilikan: pastikan jadwal milik user yang sedang login
        if ($schedule->user_id !== $request->user()->id) {
            return response()->json([
                'status'  => false,
                'message' => 'Anda tidak memiliki akses ke jadwal ini.',
                'data'    => null,
            ], 403);
        }

        // Validasi status: pastikan jadwal masih berstatus 'booked'
        if ($schedule->status !== 'booked') {
            return response()->json([
                'status'  => false,
                'message' => 'Jadwal ini tidak dapat di-check-in. Status saat ini: ' . $schedule->status,
                'data'    => null,
            ], 422);
        }

        // Gunakan database transaction untuk memastikan konsistensi data
        DB::transaction(function () use ($schedule) {
            // Ubah status jadwal peminjaman menjadi 'checked_in'
            $schedule->update(['status' => 'checked_in']);

            // Ubah status inventaris menjadi 'borrowed'
            $schedule->inventory->update(['status' => 'borrowed']);
        });

        // Refresh data setelah update & load relasi yang dibutuhkan untuk email
        $schedule->refresh();
        $schedule->load(['inventory', 'user']);

        // Kirim notifikasi email ke member
        $emailSent = false;
        try {
            Mail::to($schedule->user->email)->send(new CheckInSuccessMail($schedule));
            $emailSent = true;

            Log::info('Check-in email berhasil dikirim.', [
                'user_id'     => $schedule->user->id,
                'schedule_id' => $schedule->id,
            ]);
        } catch (\Exception $e) {
            // Log error tetapi jangan gagalkan proses check-in
            Log::error('Gagal mengirim email check-in.', [
                'user_id'     => $schedule->user->id,
                'schedule_id' => $schedule->id,
                'error'       => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status'     => true,
            'message'    => 'Check-in berhasil dilakukan.',
            'email_sent' => $emailSent,
            'data'       => [
                'schedule'  => $schedule,
                'inventory' => $schedule->inventory,
            ],
        ], 200);
    }
}
