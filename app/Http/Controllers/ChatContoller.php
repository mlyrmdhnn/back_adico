<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRoomChat;
use Illuminate\Http\Request;

class ChatContoller extends Controller
{
    public function send(Request $request)
    {
        UserRoomChat::create([
            'from' => auth()->user()->id,
            'to' => User::where('role', 'developer')->first()->id,
            'type' => 'user',
            'isRead' => false,
            'chat' => $request->message
        ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'success to send message'
        ],201);
    }

    public function getUserChat()
    {
        $developerId = User::where('role', 'developer')->first()->id;
        $userId = auth()->id();

        $chats = UserRoomChat::where(function ($q) use($userId, $developerId) {
            $q->where('from', $userId)->where('to', $developerId);
        })->orWhere(function ($q) use ($userId, $developerId) {
            $q->where('from', $developerId)
            ->where('to', $userId);
        })->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $chats
        ]);
    }

    public function devDetailChat(Request $request)
    {
        $userId = $request->user_id;
        $developerId = auth()->id();


        $chats = UserRoomChat::where(function ($q) use($userId, $developerId) {
            $q->where('from', $userId)->where('to', $developerId);
        })->orWhere(function ($q) use ($userId, $developerId) {
            $q->where('from', $developerId)
            ->where('to', $userId);
        })->get();

        return response()->json([
            'status' => 'ok',
            'data' => $chats
        ]);
    }

    public function roomChatDev()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => User::whereIn('role', ['manager', 'supervisor', 'salesman'])->get(['id', 'name', 'role', 'code'])
        ]);
    }

    public function devSend(Request $request)
    {
        UserRoomChat::create([
            'from' => auth()->user()->id,
            'to' => $request->user_id,
            'type' => 'developer',
            'isRead' => false,
            'chat' => $request->message
        ],201);
    }

    public function userChatCountNotification()
    {
        $userId = auth()->user()->id;
        $developerId = User::where('role', 'developer')->first()->id;
        $count = UserRoomChat::where('to', $developerId)->where('isRead', 0)->count();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $count
        ]);
    }

    public function userReadChat()
    {
        $userId = auth()->user()->id;
        $chatUser = UserRoomChat::where('from', $userId)->get();

        foreach($chatUser as $c) {
            $c->update([
                'isRead' => true
            ]);
        }
        return response()->json([
            'status' => 'ok',
            'message' => 'read notification',
        ],201);
    }
}
