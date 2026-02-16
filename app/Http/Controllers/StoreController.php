<?php

namespace App\Http\Controllers;

// use App\Models\store;
use App\Models\Store as ModelsStore;
use App\Models\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StoreController extends Controller
{

    public function allStore()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => ModelsStore::with(['customer'])->latest()->get()
        ]);
    }

    public function all()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => ModelsStore::latest()->paginate(10)
        ]);
    }

    public function search($keyword)
    {
        $store = ModelsStore::where('name', 'LIKE', "%$keyword%")
        ->orWhere('address', 'LIKE', "%$keyword%")
        ->latest()->paginate(10);

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $store
        ]);
    }

    public function edit(Request $request)
    {
        $store = ModelsStore::where('id', $request->id)->first();
        $store->update([
            'name' => $request->name,
            'address' => $request->address
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
        ]);
    }

    public function create(Request $request)
    {

        $now = Carbon::now('Asia/Jakarta');

        ModelsStore::create([
            'name' => $request->name,
            'address' => $request->address,
            'customer_id' => $request->customer,
            'created_date' => $now,
            'salesman_id' => auth()->user()->id
        ]);
        return response()->json([
            'status' => 'ok',
            'message' => 'Success create store',
            $request->all()
        ]);
    }

    public function destroy(Request $request)
    {
        $id = $request->id;
        $store = ModelsStore::where('id', $id)->first();
        $store->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'success delete product',
        ]);
    }

    public function detail($id)
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => ModelsStore::where('id', $id)->first()
        ]);
    }

    public function storeMonthly()
    {
        $now = Carbon::now();

        $stores = ModelsStore::where('salesman_id', auth()->user()->id)
            ->whereYear('created_date', $now->year)
            ->whereMonth('created_date', $now->month)
            ->orderBy('created_date', 'asc')
            ->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => [
                'year' => $now->year,
                'month' => $now->month,
                'total_store' => $stores->count(),
                'stores' => $stores
            ]
        ]);
    }

    public function storeRangeRecap(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end'   => 'required|date',
        ]);

        $stores = ModelsStore::where('salesman_id', auth()->user()->id)
            ->whereBetween('created_date', [
                Carbon::parse($request->start)->startOfDay(),
                Carbon::parse($request->end)->endOfDay(),
            ])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => [
                'stores' => $stores
            ]
        ]);
    }
}
