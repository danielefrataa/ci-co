<?php

namespace App\Http\Middleware;
use Illuminate\Http\Request;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Pastikan user login dan memiliki salah satu role yang diizinkan
        if (Auth::check() && in_array(Auth::user()->role, $roles)) {
            return $next($request);
        }

        // Jika user login tetapi role salah, logout dan redirect ke login
        if (Auth::check()) {
            Auth::logout(); // Logout user
            return redirect('/login')
                ->with('error', 'Role Anda tidak sesuai. Silakan login kembali dengan akun yang sesuai.');
        }

        // Jika user tidak login
        return redirect('/login')->with('error', 'Silakan login terlebih dahulu.');
    }
}

