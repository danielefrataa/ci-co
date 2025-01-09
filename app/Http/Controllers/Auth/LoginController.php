<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;  // Pastikan untuk menambahkan use statement ini

class LoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        // Validate the request input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Retrieve user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and password matches
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Log the user in with the default guard
            Auth::login($user);
            Log::info('User logged in: ' . $user->name . ' (' . $user->email . ') - Role: ' . $user->role);  // Log username, email, and role

            // Redirect to the specific dashboard based on role
            switch ($user->role) {
                case 'frontoffice':
                    return redirect()->intended('/front-office/dashboard');
                case 'marketing':
                    return redirect()->intended('/marketing/peminjaman');
                case 'IT':
                    return redirect()->intended('/it/users');
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

        // If login fails, redirect back with an error
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handle logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
