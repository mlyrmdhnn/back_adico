<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Uom extends Model
{
    /** @use HasFactory<\Database\Factories\UomFactory> */
    use HasFactory;
    protected $fillable = ['name'];
    protected $table = 'uoms';

    public function product() :HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function requestItems() :HasMany
    {
        return $this->hasMany(RequestItems::class);
    }
}
