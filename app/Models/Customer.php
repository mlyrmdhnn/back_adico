<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    /** @use HasFactory<\Database\Factories\CustomerFactory> */
    use HasFactory;
    protected $table = 'customer';
    protected $guarded = [];

    public function store() :HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function createdBySalesman() :BelongsTo
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

}
