<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * GET /api/admin/members
     * Mengambil daftar member (role: member) untuk lookup.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::where('role', 'member')->select('id', 'name', 'email');

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        // Tidak perlu pagination jika ini hanya untuk opsi dropdown, atau kita paginate
        $members = $query->orderBy('name', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar member berhasil diambil.',
            'data' => $members,
        ], 200);
    }
}
