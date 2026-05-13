<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Redirect setelah login berhasil — ke admin dashboard.
     */
    protected $redirectTo = '/admin/dashboard';
}
