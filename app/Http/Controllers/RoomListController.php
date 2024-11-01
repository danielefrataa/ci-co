<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoomListController extends Controller
{
    //
    public function show()
    {
        return view('front_office.roomList');
    }
}
