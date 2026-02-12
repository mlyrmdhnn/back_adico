<?php

namespace App\Http\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService {

    public function editProffile($data)
    {
        $user = User::where('id', $data['id'])->first();
        $user->update([
            'name' => $data['name'],
            'phone' => $data['phone']
        ]);
    }

    public function changePassword(User $user, array $data): void
    {
        if (!Hash::check($data['oldPass'], $user->password)) {
            throw new \Exception('Incorrect current password');
        }

        $user->update([
            'password' => Hash::make($data['newPass']),
        ]);
    }


}