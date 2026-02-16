<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\Requests;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    /** @use HasFactory<\Database\Factories\StoreFactory> */
    use HasFactory;
    protected $guarded = [];

    public function requests() :HasMany
    {
        return $this->hasMany(Requests::class);
    }

    public function customer() :BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function createdBySalesman() :BelongsTo
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }
}
