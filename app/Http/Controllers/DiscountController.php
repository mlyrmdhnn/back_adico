<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RequestItems;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function discount2(Request $request)
    {
        // return response()->json($request->all());
        $itemRequest = RequestItems::where('id', $request->id)->first();
        $itemRequest->update([
            'discount2' => $request->discount2
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ]);
    }

    public function discount1(Request $request)
    {
        $product1 = Product::where('id', $request->id)->first();
        $product1->update([
            'discount1' => $request->discount1
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
        ]);
    }

    public function create1(Request $request)
    {
        Product::query()->update([
            'discount1' => $request->discount1
        ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ]);
    }
}
