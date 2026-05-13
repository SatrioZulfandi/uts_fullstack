<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\BorrowingSchedule;
use Illuminate\Http\Request;

/**
 * Controller untuk Admin Dashboard.
 * Menyediakan statistik ringkasan sistem.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $totalInventories      = Inventory::count();
        $availableInventories  = Inventory::where('status', 'available')->count();
        $maintenanceInventories = Inventory::where('status', 'maintenance')->count();
        $activeSchedules       = BorrowingSchedule::where('status', 'confirmed')->count();

        $recentInventories = Inventory::latest()->take(5)->get();
        $recentSchedules   = BorrowingSchedule::with(['user', 'inventory'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalInventories',
            'availableInventories',
            'maintenanceInventories',
            'activeSchedules',
            'recentInventories',
            'recentSchedules'
        ));
    }
}
