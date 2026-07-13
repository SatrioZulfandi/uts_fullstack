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

        $search = $request->input('search');

        $query->when($search, function ($query, $search) {
            $pattern = '%'.$search.'%';

            $query->where(function ($subQuery) use ($pattern) {
                $subQuery
                    ->whereLike('name', $pattern)
                    ->orWhereLike('email', $pattern);
            });
        });

        // Tidak perlu pagination jika ini hanya untuk opsi dropdown, atau kita paginate
        $members = $query->orderBy('name', 'asc')->get();

        return response()->json([
            'status' => true,
            'message' => 'Daftar member berhasil diambil.',
            'data' => $members,
        ], 200);
    }
}
