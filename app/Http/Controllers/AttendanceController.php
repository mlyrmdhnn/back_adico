<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    public function createAttendance(Request $request)
    {
        $this->authorize('createAttendance', User::class);
        AttendanceType::create([
            'type' => $request->attendance
        ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ],201);
    }

    public function create(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');
        $user = auth()->user()->id;
        $day = $now->copy()->startOfDay();

        if(Attendance::where('salesman_id', $user)->whereDate('date', $day)->exists()) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Already attendance today'
            ], 409);
        }

        $deadline = Carbon::createFromTime(8, 0, 0, 'Asia/Jakarta');
        $attendanceTypeId = $now->greaterThan($deadline) ? 4 : $request->id;

        Attendance::create([
            'salesman_id' => $user,
            'date' => $now,
            'check_in_at' => $now,
            'attendance_type_id' => $attendanceTypeId,
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Attendance recorded',
        ], 201);
    }

    public function izin()
    {
        $now = Carbon::now('Asia/Jakarta');
        $user = auth()->user()->id;
        $day = Carbon::today('Asia/Jakarta');

        if(Attendance::where('salesman_id', $user)->where('date',$day)->first())
        {
            return response()->json([
                'status' => 'ok',
                'message' => 'Already attendance today'
            ],409);
        }

        Attendance::create([
            'salesman_id' => $user,
            'date' => $now,
            'check_in_at' => Carbon::now('Asia/Jakarta'),
            'attendance_type_id' => 2,
        ]);
    }

    public function sakit()
    {
        $now = Carbon::now('Asia/Jakarta');
        $user = auth()->user()->id;
        $day = Carbon::today('Asia/Jakarta');

        if(Attendance::where('salesman_id', $user)->where('date',$day)->first())
        {
            return response()->json([
                'status' => 'ok',
                'message' => 'Already attendance today'
            ],409);
        }

        Attendance::create([
            'salesman_id' => $user,
            'date' => $now,
            'check_in_at' => Carbon::now('Asia/Jakarta'),
            'attendance_type_id' => 3,
        ]);
    }

    public function show($date)
    {

        $attendance = Attendance::with(['salesman', 'type'])->where('date', $date)->get();

        if(!$attendance || $attendance->count() < 1) {
            return response()->json([
                'status' => 'ok',
                'message' => 'Not found attendance by date'
            ],404);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $attendance
        ]);

    }

    public function today()
    {
        $now = Carbon::today('Asia/Jakarta');
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Attendance::with(['salesman', 'type'])->where('date', $now)->get()
        ]);
    }

    public function type()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => AttendanceType::get()
        ]);
    }

    public function editType(Request $request)
    {
        $attendance = AttendanceType::where('id', $request->id)->first();
        $attendance->update([
            'type' => $request->val
        ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ]);
    }

    public function edit(Request $request)
    {
        $day = Carbon::now('Asia/Jakarta')->toDateString();
        $attendance = Attendance::where('salesman_id', $request->salesman_id)->where('date', $day)->first();

        if ($attendance) {
            $attendance->update([
                'attendance_type_id' => $request->type_id,
            ]);

            return response()->json([
                'status' => 'ok',
                'message' => 'Attendance updated',
                'data' => $attendance
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Attendance not found',
        ], 404);
    }
}
