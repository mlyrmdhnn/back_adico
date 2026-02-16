<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\NotificationService;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;

class NotificationController extends Controller
{
    public function createGlobal(Request $request, NotificationService $service)
    {
        $credentials = $request->validate([
            'title' => 'required|max:30',
            'description' => 'required|max:255'
        ]);

        $notif = $service->createGlobal($credentials);
        return response()->json([
            'status' => 'ok',
            'message' => 'success to create notification'
        ],201);
    }

    public function notifCreate(Request $request)
    {
        $credentials = $request->validate([
            'title' => 'required|max:30',
            'description' => 'required|max:255',
            'user_id' => 'required'
        ]);

        Notifications::create([
            'title' => $credentials['title'],
            'description' => $credentials['description'],
            'user_id' => $credentials['user_id'],
            'isRead' => false,
            'type' => 'personal'
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'success to create notification'
        ],201);
    }

    public function salesmanNotif()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Notifications::where('user_id', auth()->user()->id)->latest()->get()
        ]);
    }

    public function count()
    {
        $this->authorize('countNotif', User::class);
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Notifications::where('user_id', auth()->user()->id)->where('isRead', 0)->count()
        ]);
    }

    public function readNotif()
    {
        $user = auth()->user()->id;
        $notif = Notifications::where('user_id', $user)->latest()->get();
        foreach($notif as $n) {
            $n->update([
                'isRead' => true
            ]);
        };
        return response()->json([
            'status' => 'ok',
            'message' => 'read notification',
        ],201);
    }

}
