<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserChat extends Model
{
    /** @use HasFactory<\Database\Factories\UserChatFactory> */
    use HasFactory;
    protected $guarded = [];
    protected $table = 'user_chat';

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
