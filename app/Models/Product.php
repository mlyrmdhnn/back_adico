<?php

namespace App\Models;

use App\Models\Brand;
use App\Models\Configuration;
use App\Models\Uom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'brand_id',
        'configuration_id',
        'uom_id',
        'satuan_uom',
        'karton',
        'barcode',
        'discount1'
    ];

    public function brand() :BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function uom() :BelongsTo
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }

    public function configuration() :BelongsTo
    {
        return $this->belongsTo(Configuration::class, 'configuration_id');
    }
}
