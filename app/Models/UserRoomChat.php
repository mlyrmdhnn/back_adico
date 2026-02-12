<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRoomChat extends Model
{
    /** @use HasFactory<\Database\Factories\UserRoomChatFactory> */
    use HasFactory;
    protected $guarded = [];
    protected $table = 'user_room_chat';

}
