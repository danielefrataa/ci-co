<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontOfficeLoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.front_office_login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt to log the user in
        if (Auth::attempt($credentials)) {
            // Redirect to front office dashboard on successful login
            return redirect()->intended('/front-office/dashboard');
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
        return redirect('/front-office/login');
    }
}
