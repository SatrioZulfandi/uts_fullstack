<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\BorrowingSchedule;
use App\Models\Inventory;

/**
 * Controller untuk Member Dashboard.
 * Menampilkan statistik dan ringkasan untuk member.
 */
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalSchedules = BorrowingSchedule::where('user_id', $user->id)->count();
        $activeSchedules = BorrowingSchedule::where('user_id', $user->id)
            ->whereIn('status', ['booked', 'checked_in'])
            ->count();
        $completedSchedules = BorrowingSchedule::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();
        $availableInventories = Inventory::where('status', 'available')->count();

        $upcomingSchedules = BorrowingSchedule::with('inventory')
            ->where('user_id', $user->id)
            ->whereIn('status', ['booked', 'pending'])
            ->orderBy('start_time')
            ->take(5)
            ->get();

        return view('member.dashboard', compact(
            'totalSchedules',
            'activeSchedules',
            'completedSchedules',
            'availableInventories',
            'upcomingSchedules'
        ));
    }
}
