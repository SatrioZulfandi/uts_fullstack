<?php

namespace App\Http\Controllers;

use App\Http\Requests\InventoryRequest;
use App\Models\Inventory;

/**
 * Resource Controller untuk mengelola data Inventaris (Admin Dashboard).
 * Menyediakan fungsi CRUD lengkap untuk workspace dan equipment.
 */
class InventoryController extends Controller
{
    /**
     * Menampilkan daftar semua inventaris.
     */
    public function index()
    {
        $inventories = Inventory::latest()->paginate(10);

        return view('admin.inventories.index', compact('inventories'));
    }

    /**
     * Menampilkan form untuk menambah inventaris baru.
     */
    public function create()
    {
        return view('admin.inventories.create');
    }

    /**
     * Menyimpan data inventaris baru ke database.
     * Menggunakan InventoryRequest untuk validasi input.
     */
    public function store(InventoryRequest $request)
    {
        // Data sudah tervalidasi oleh InventoryRequest
        Inventory::create($request->validated());

        return redirect()
            ->route('admin.inventories.index')
            ->with('success', 'Inventaris berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail satu inventaris.
     */
    public function show(Inventory $inventory)
    {
        // Eager load jadwal peminjaman terkait
        $inventory->load('borrowingSchedules.user');

        return view('admin.inventories.show', compact('inventory'));
    }

    /**
     * Menampilkan form edit inventaris.
     */
    public function edit(Inventory $inventory)
    {
        return view('admin.inventories.edit', compact('inventory'));
    }

    /**
     * Memperbarui data inventaris di database.
     * Menggunakan InventoryRequest untuk validasi input.
     */
    public function update(InventoryRequest $request, Inventory $inventory)
    {
        // Data sudah tervalidasi oleh InventoryRequest
        $inventory->update($request->validated());

        return redirect()
            ->route('admin.inventories.index')
            ->with('success', 'Inventaris berhasil diperbarui.');
    }

    /**
     * Menghapus data inventaris dari database.
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()
            ->route('admin.inventories.index')
            ->with('success', 'Inventaris berhasil dihapus.');
    }
}
