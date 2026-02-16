<?php

namespace App\Models;

use App\Models\Product;
use App\Models\RequestItems;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Configuration extends Model
{
    /** @use HasFactory<\Database\Factories\ConfigurationFactory> */
    use HasFactory;
    protected $fillable = ['configuration'];
    protected $table = 'configurations';

    public function product() :HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function requestItems() :HasMany
    {
        return $this->hasMany(RequestItems::class);
    }
}
