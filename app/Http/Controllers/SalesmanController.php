<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\SalesmanService;
use App\Models\HariKerja;
use Carbon\Carbon;
use App\Http\Controllers\Controller;


class SalesmanController extends Controller
{
    public function create(Request $request, SalesmanService $service)
    {
        $this->authorize('create', User::class);

        $credentials = $request->validate([
            'username' => 'unique:users,username,except,id|required|min:4',
            'phone' => 'required',
            'name' => 'required|max:20',
            'password' => 'required|min:6',
            'hari_kerja' => 'required',
            'target' => 'required'
        ]);

        $salesman = $service->create($credentials);

        if(!$salesman) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Failed to created salesman'
            ],200);
        }
        return response()->json([
            'status' => 'ok',
            'message' => 'Success create salesman',
            'data' => $salesman
        ],201);
    }

    public function allSalesman()
    {
        // $this->authorize('viewAny', User::class);
        return response()->json([
            'status' => 'ok',
            'message' => 'Success',
            'data' => User::with(['hariKerja'])->where('role', 'salesman')->latest()->get()
        ]);
    }

    public function deleteSalesman(Request $request, SalesmanService $service)
    {
        $service->delete($request->id);
        return response()->json([
            'status' => 'ok',
            'message' => 'Success delete salesman'
        ],200);
    }

    public function hariKerja(Request $request)
    {
        $this->authorize('inputHariKerja', User::class);
        $hariKerja = $request->hariKerja;
        $dateParsing = Carbon::now('Asia/Jakarta')->startOfMonth();
        $user = User::where('role', 'salesman')->get();

        foreach($user as $u)
        {
            HariKerja::create([
                'day' => $hariKerja,
                'period' => $dateParsing,
                'salesman_id' => $u->id
            ]);
        }

        return response()->json([
            'status' => 'success'
        ]);
    }

}
