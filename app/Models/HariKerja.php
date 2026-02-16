<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HariKerja extends Model
{
    /** @use HasFactory<\Database\Factories\HariKerjaFactory> */
    use HasFactory;
    protected $fillable = ['day', 'salesman_id', 'period'];
    protected $table = 'hari_kerja';

    public function salesman() :BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
