<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ChatController extends Controller
{
    public function getContactList()
    {
        $users = User::all();
        return response()->json([
            'users' => $users
        ],200);
    }
}
