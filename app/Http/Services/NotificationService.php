<?php

namespace App\Http\Services;

use App\Models\Notifications;
use App\Models\User;

class NotificationService {
    public function createGlobal(array $data)
    {
        $user = User::where('role', 'salesman')->get();
        foreach($user as $u) {
            Notifications::create([
                'user_id' => $u->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'isRead' => false,
                'type' => 'global'
            ]);
        }
    }
}
