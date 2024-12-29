<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleRedirect
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $role = Auth::user()->role;

            switch ($role) {
                case 'frontoffice':
                    return redirect()->intended('/front-office/dashboard');
                case 'marketing':
                    return redirect()->intended('/marketing/peminjaman');
                case 'it':
                    return redirect()->intended('/it/home');
                case 'produksi':
                    return redirect()->intended('/production/peminjaman');
                case 'kadin': // Redirect for Kepala Dinas
                    return redirect()->intended('/dinas/approve');
                case 'kabid': // Redirect for Kepala Bidang
                    return redirect()->intended('/dinas/approve');
                default:
                    return redirect()->intended('/home'); // Default home for other roles
            }
        }

        return $next($request);
    }
}
