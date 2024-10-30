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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:front_office'],
            'password' => ['required', 'string', 'confirmed'],
            'created_at' => now(), // Optional: Track when the user was created
            'updated_at' => now(),
        ]);

        // Create a new user instance
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password
            'created_at' => now(), // Optional: Track when the user was created
            'updated_at' => now(),
        ]);

        // Redirect to the desired page after registration
        return redirect()->route('front_office.login')->with('success', 'Registration successful! You can now log in.');
    }
}
