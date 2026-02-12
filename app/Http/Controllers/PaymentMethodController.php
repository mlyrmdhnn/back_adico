<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function create(Request $request)
    {
        $this->authorize('createPaymentMethod', User::class);
        PaymentMethod::create([
            'name' => $request->val
        ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
        ],201);
    }

    public function edit(Request $request)
    {
        $this->authorize('editPaymentMethod', User::class);
        $paymentMethod = PaymentMethod::where('id', $request->id)->first();
        $paymentMethod->update([
            'name' => $request->value
        ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ],201);
    }
}
