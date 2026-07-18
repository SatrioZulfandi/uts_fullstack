<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware untuk membatasi akses hanya untuk user dengan role 'member'.
 */
class MemberMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login dan memiliki role member
        if (! auth()->check() || auth()->user()->role !== 'member') {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke fitur ini.',
                    'data' => null,
                ], 403);
            }

            // Jika admin, redirect ke admin dashboard
            if (auth()->check() && auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('error', 'Fitur ini hanya untuk member.');
            }

            abort(403, 'Akses ditolak. Hanya member yang diizinkan.');
        }

        return $next($request);
    }
}
