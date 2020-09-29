<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Events\NewMessage;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::where(function($query) {
            $query->where('from', request('from'));
            $query->where('to', request('to'));
        })->orWhere(function($query) {
            $query->where('from', request('to'));
            $query->where('to', request('from'));
        })->get();

        return response()->json([
            'messages'=> $messages->load('from','to')
        ],200);
    }

    public function store(){
        request()->validate([
            'from' => 'required',
            'to' => 'required',
            'text' => 'required',
        ]);
        $data = request(['from','to','text']);
        $message = Message::create($data);

        //broadcast Event
        broadcast(new NewMessage($message->load('from')))->toOthers();

        return response()->json([
            'message' => $message->load('from')
        ], 201);
    }
}
