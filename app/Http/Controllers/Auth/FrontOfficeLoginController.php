<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\FrontOffice;

class FrontOfficeLoginController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.fo_login');
    }

    // Show Dashboard
    public function showFoDashboard()
    {
        return view('front_office.dashboard');
    }

    // Handle login
    public function login(Request $request)
{
    // Validate the request input
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // Retrieve user from the front_office table
    $user = FrontOffice::where('email', $credentials['email'])->first();

    // Check if user exists and password matches
    if ($user && Hash::check($credentials['password'], $user->password)) {
        // Log the user in via the front_office guard
        Auth::guard('front_office')->login($user);

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
