<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceFactory> */
    use HasFactory;
    protected $guarded = [];
    protected $table = 'attendance';

    public function salesman() :BelongsTo
    {
        return $this->belongsTo(User::class, 'salesman_id');
    }

    public function type() :BelongsTo
    {
        return $this->belongsTo(AttendanceType::class, 'attendance_type_id');
    }

}
