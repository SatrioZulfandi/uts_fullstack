<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\BorrowingSchedule;

/**
 * Controller untuk Member mengelola jadwal peminjaman mereka sendiri.
 * Member hanya bisa melihat dan check-in jadwal miliknya.
 */
class ScheduleController extends Controller
{
    /**
     * Menampilkan daftar jadwal peminjaman milik member.
     */
    public function index()
    {
        $schedules = BorrowingSchedule::with('inventory')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('member.schedules.index', compact('schedules'));
    }

    /**
     * Menampilkan form untuk membuat peminjaman baru.
     */
    public function create()
    {
        $inventories = \App\Models\Inventory::where('status', 'available')->get();
        return view('member.schedules.create', compact('inventories'));
    }

    /**
     * Menyimpan data peminjaman baru (status otomatis pending).
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
        ], [
            'inventory_id.required' => 'Inventaris wajib dipilih.',
            'start_time.required' => 'Waktu mulai wajib diisi.',
            'start_time.after_or_equal' => 'Waktu mulai harus dari sekarang atau ke depan.',
            'end_time.required' => 'Waktu selesai wajib diisi.',
            'end_time.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        BorrowingSchedule::create($validated);

        return redirect()
            ->route('member.schedules.index')
            ->with('success', 'Permintaan peminjaman berhasil dibuat. Silakan tunggu konfirmasi admin.');
    }

    /**
     * Menampilkan detail jadwal peminjaman.
     */
    public function show(BorrowingSchedule $schedule)
    {
        // Pastikan member hanya bisa lihat jadwal miliknya
        if ($schedule->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        $schedule->load('inventory');

        return view('member.schedules.show', compact('schedule'));
    }

    /**
     * Check-in (konfirmasi pengambilan barang).
     */
    public function checkIn(BorrowingSchedule $schedule)
    {
        // Pastikan member hanya bisa check-in jadwal miliknya
        if ($schedule->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        if ($schedule->status !== 'booked') {
            return back()->with('error', 'Hanya jadwal dengan status booked yang dapat di-check-in.');
        }

        $schedule->update(['status' => 'checked_in']);

        return back()->with('success', 'Berhasil check-in. Selamat menggunakan fasilitas!');
    }

    /**
     * Melakukan check-out untuk jadwal yang sudah di-check-in.
     */
    public function checkOut(BorrowingSchedule $schedule)
    {
        // Pastikan jadwal milik user yang sedang login
        if ($schedule->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke jadwal ini.');
        }

        if ($schedule->status !== 'checked_in') {
            return back()->with('error', 'Hanya jadwal dengan status checked_in yang dapat di-check-out.');
        }

        $schedule->update(['status' => 'completed']);

        return back()->with('success', 'Berhasil check-out. Terima kasih telah menggunakan fasilitas!');
    }
}
