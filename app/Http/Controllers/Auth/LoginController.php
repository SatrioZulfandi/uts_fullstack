<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect setelah login berhasil — berdasarkan role user.
     */
    protected function redirectTo(): string
    {
        $user = auth()->user();

        if ($user && $user->isAdmin()) {
            return '/admin/dashboard';
        }

        return '/member/dashboard';
    }

    /**
     * The user has been authenticated.
     * Redirect berdasarkan role.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->isAdmin()) {
            return redirect('/admin/dashboard');
        }

        return redirect('/member/dashboard');
    }
}
