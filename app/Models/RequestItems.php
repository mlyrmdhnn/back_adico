<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestItems extends Model
{
    /** @use HasFactory<\Database\Factories\RequestItemsFactory> */
    use HasFactory;
    protected $guarded = [];

    public function request() :BelongsTo
    {
        return $this->belongsTo(Requests::class , 'request_id');
    }

    public function product() :BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }

}
