<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\MessageSent;
use Illuminate\Support\Facades\Log;

class ChatTestController extends Controller
{
    public function index()
    {
        return view('admin.testing-only.chat');
    }

    public function broadcast(Request $request)
    {
        $username = $request->input('username');
        $message = $request->input('message');

        broadcast(new MessageSent($username, $message));

        return response()->json(['status' => 'Message broadcasted']);
    }


    
}
