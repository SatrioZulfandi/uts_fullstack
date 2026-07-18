<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Inventory;

/**
 * Controller untuk Member melihat inventaris yang tersedia.
 * Hanya read-only, member tidak bisa CRUD inventaris.
 */
class InventoryController extends Controller
{
    /**
     * Menampilkan daftar inventaris yang tersedia.
     */
    public function index()
    {
        $inventories = Inventory::where('status', 'available')
            ->latest()
            ->paginate(10);

        return view('member.inventories.index', compact('inventories'));
    }
}
