<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceType extends Model
{
    /** @use HasFactory<\Database\Factories\AttendanceTypeFactory> */
    use HasFactory;
    protected $table = 'attendance_type';
    protected $fillable = ['type'];
}
