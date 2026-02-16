<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTargets extends Model
{
    /** @use HasFactory<\Database\Factories\SalesTargetsFactory> */
    use HasFactory;
    protected $guarded = [];

    public function salesman() :BelongsTo
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }
}
