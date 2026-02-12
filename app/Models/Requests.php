<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Requests extends Model
{
    /** @use HasFactory<\Database\Factories\RequestsFactory> */
    use HasFactory;
    protected $guarded = [];

    public function salesman() :BelongsTo
    {
        return $this->belongsTo(User::class, 'salesman_id')->withTrashed();
    }

    public function store() :BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function requestItems() :HasMany
    {
        return $this->hasMany(RequestItems::class,'request_id');
    }

    public function paymentMethod() :BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function manager() :BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function supervisor() :BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
