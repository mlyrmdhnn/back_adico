<?php

namespace App\Http\Services;

use App\Models\HariKerja;
use App\Models\SalesTargets;
use Carbon\Carbon;
use App\Models\User;
use App\Models\UserChat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SalesmanService {
    public function create(array $data)
    {
       DB::transaction(function() use ($data) {

        $dateParsing = Carbon::now('Asia/Jakarta')->startOfMonth();
        $count = User::count();
        $user =  User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'],
            'code' => 'TO-' . $count,
            'role' => 'salesman'
        ]);

        UserChat::create([
            'user_id' => $user->id
        ]);

        HariKerja::create([
            'day' => $data['hari_kerja'],
            'period' => $dateParsing,
            'salesman_id' => $user->id
        ]);

        SalesTargets::create([
            'salesman_id' => $user->id,
            'period_type' => 'monthly',
            'period_start' => Carbon::now('Asia/Jakarta'),
            'target_value' => $data['target'],
            'status' => 'active',
            'created_by' => auth()->user()->id
        ]);

       });
    }
    public function delete($id)
    {
        $user = User::where('id', $id)->first();
        return $user->delete();
    }
}