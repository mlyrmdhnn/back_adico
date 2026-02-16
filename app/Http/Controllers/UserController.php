<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\UserService;

class UserController extends Controller
{
    public function edit(Request $request, UserService $service)
    {
        $service->editProffile($request->all());
        if(!$service) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed edit proffile'
            ]);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'Sucess edit proffile'
        ]);
    }

    public function changePassword(Request $request, UserService $service)
    {
        $idUser = auth()->user()->id;
        $target = User::findOrFail($idUser);
        $this->authorize('editProffile', $target);
        $user = $service->changePassword($target, $request->all());


        return response()->json([
            'status' => 'ok',
            'message' => 'Password updated successfully',
        ]);
    }

    public function getUser()
    {
        $this->authorize('viewAny', User::class);
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => User::where('role', 'salesman')->latest()->get(['id', 'code', 'name', 'role', 'phone'])
        ]);
    }
}
