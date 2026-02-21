<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserChat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Http\Services\CustomerCodeService;
use Carbon\Carbon;
use Illuminate\Support\Str;

// use Pest\Support\Str;


class SupervisorController extends Controller
{
    public function getSupervisor()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => User::where('role', 'supervisor')->latest()->get()
        ],200);
    }

    public function createSuperVisor(Request $request, CustomerCodeService $service)
    {
        $this->authorize('createSupervisor', User::class);
        $credentials = $request->validate([
            'username' => 'unique:users,username|required|min:4|max:16',
            'name' => 'required',
            'password' => 'required|min:4|max:14',
            'phone' => 'required'
        ]);
        DB::transaction(function() use ($request, $service) {
            $code = $service->generate($request->username);
            $user = User::create([
                'code' => $code,
                'username' => $request->username,
                'name' => $request->name,
                'phone' => $request->phone,
                'role' => 'supervisor',
                'password' => Hash::make($request->password)
            ]);

            UserChat::create([
                'user_id' => $user->id
            ]);
        });

        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ],201);
    }

    public function deleteSuperVisor(Request $request)
    {
        $this->authorize('deleteSupervisor', User::class);
        DB::transaction(function () use ($request) {
            $user = User::where('id', $request->id)->first();
            $user->update([
                'username' => Carbon::now()->getTimestampMs()
            ]);
            $user->delete();
        });
        return response()->json([
            'status' => 'ok',
            'message' => 'Success to delete supervisor'
        ], 201);
    }
}
