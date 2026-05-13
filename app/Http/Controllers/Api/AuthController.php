<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * API Controller untuk autentikasi member.
 * Menangani login dan penerbitan Bearer Token via Laravel Sanctum.
 */
class AuthController extends Controller
{
    /**
     * POST /api/login
     * Autentikasi member dan mengembalikan Bearer Token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        // Validasi input login
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        // Cari user berdasarkan email
        $user = User::where('email', $request->email)->first();

        // Verifikasi kredensial: user harus ada dan password cocok
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah.',
                'data'    => null,
            ], 401);
        }

        // Buat token Sanctum untuk user yang berhasil login
        $token = $user->createToken('smart-hub-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'user'  => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ],
        ], 200);
    }

    /**
     * POST /api/logout
     * Menghapus token yang sedang digunakan (opsional).
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        // Hapus token saat ini
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil.',
            'data'    => null,
        ], 200);
    }
}
