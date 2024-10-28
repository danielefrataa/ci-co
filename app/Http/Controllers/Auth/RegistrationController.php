<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.fo_register'); // Create this view
    }

    public function register(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:front_office'],
            'password' => ['required', 'string', 'confirmed'], // Ensure password confirmation
        ]);

        // Create a new user instance
        DB::table('front_office')->insert([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
        ]);

        // Redirect to the desired page after registration
        return redirect()->route('front_office.login')->with('success', 'Registration successful! You can now log in.');
    }
}
