<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HashFrontOfficePasswords extends Migration
{
    public function up()
    {
        // Retrieve all users from the front_office table
        $users = DB::table('front_office')->get();

        foreach ($users as $user) {
            // Update each user's password to be hashed
            DB::table('front_office')
                ->where('email', $user->email)
                ->update(['password' => Hash::make($user->password)]);
        }
    }

    public function down()
    {
        // Optionally, you can provide logic to revert the changes if needed.
    }
}

