<?php

namespace App\Http\Controllers;

use App\Http\Requests\ScheduleRequest;
use App\Models\BorrowingSchedule;
use App\Models\Inventory;
use App\Models\User;

/**
 * Resource Controller untuk mengelola Jadwal Peminjaman (Admin Dashboard).
 * Menyediakan fungsi CRUD lengkap untuk borrowing schedules.
 */
class ScheduleController extends Controller
{
    /**
     * Menampilkan daftar semua jadwal peminjaman.
     */
    public function index()
    {
        // Eager load relasi user dan inventory untuk menghindari N+1 query
        $schedules = BorrowingSchedule::with(['user', 'inventory'])
            ->latest()
            ->paginate(10);

        return view('admin.schedules.index', compact('schedules'));
    }

    /**
     * Menampilkan form untuk membuat jadwal peminjaman baru.
     */
    public function create()
    {
        $users = User::where('role', 'member')->get();
        $inventories = Inventory::where('status', 'available')->get();

        return view('admin.schedules.create', compact('users', 'inventories'));
    }

    /**
     * Menyimpan jadwal peminjaman baru ke database.
     * Menggunakan ScheduleRequest untuk validasi input.
     */
    public function store(ScheduleRequest $request)
    {
        // Data sudah tervalidasi oleh ScheduleRequest
        BorrowingSchedule::create($request->validated());

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal peminjaman berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu jadwal peminjaman.
     */
    public function show(BorrowingSchedule $schedule)
    {
        // Eager load relasi terkait
        $schedule->load(['user', 'inventory']);

        return view('admin.schedules.show', compact('schedule'));
    }

    /**
     * Menampilkan form edit jadwal peminjaman.
     */
    public function edit(BorrowingSchedule $schedule)
    {
        $users = User::where('role', 'member')->get();
        $inventories = Inventory::all();

        return view('admin.schedules.edit', compact('schedule', 'users', 'inventories'));
    }

    /**
     * Memperbarui data jadwal peminjaman di database.
     * Menggunakan ScheduleRequest untuk validasi input.
     */
    public function update(ScheduleRequest $request, BorrowingSchedule $schedule)
    {
        // Data sudah tervalidasi oleh ScheduleRequest
        $schedule->update($request->validated());

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal peminjaman berhasil diperbarui.');
    }

    /**
     * Menghapus jadwal peminjaman dari database.
     */
    public function destroy(BorrowingSchedule $schedule)
    {
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index')
            ->with('success', 'Jadwal peminjaman berhasil dihapus.');
    }
}
