<?php

namespace App\Http\Controllers;

use App\Http\Services\CustomerService;
use App\Models\Customer;
use App\Models\DataCustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerController extends Controller
{

    public function allCust()
    {
        return response()->json([
            'data' => Customer::latest()->get()
        ]);
    }

    public function detail($id)
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Customer::with(['store'])->where('id', $id)->first()
        ]);
    }

    public function show()
    {
        // $this->authorize('viewAny', DataCustomer::class);
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Customer::with(['store'])->latest()->paginate(10)
        ]);
    }

    public function search($keyword, CustomerService $service)
    {
        $customer = $service->searchCustomer($keyword);
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $customer
        ]);
    }

    public function delete(Request $request)
    {
        $this->authorize('delete', DataCustomer::class);
        $customer = DataCustomer::where('id', $request->id)->first();
        $customer->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Success delete customer'
        ]);
    }

    public function create(Request $request)
    {
        $now = Carbon::now('Asia/Jakarta');

        Customer::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'npwp' => $request->npwp,
            'salesman_id' => auth()->user()->id,
            'created_date' => $now
            // 'store_id' => $request->storeId
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ],201);
    }

    public function customerMonthly()
    {
        $now = Carbon::now();

        $customers = Customer::where('salesman_id', auth()->user()->id)
        ->whereYear('created_date', $now->year)
        ->whereMonth('created_at', $now->month)
        ->orderBy('created_date', 'asc')->get();

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'year' => $now->year,
            'month' => $now->month,
            'data' => $customers
        ]);
    }

    public function customerRangeRecap(Request $request)
    {
        $request->validate([
            'start' => 'required|date',
            'end'   => 'required|date',
        ]);

        $customers = Customer::where('salesman_id', auth()->user()->id)
            ->whereBetween('created_date', [
                Carbon::parse($request->start)->startOfDay(),
                Carbon::parse($request->end)->endOfDay(),
            ])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'status'  => 'ok',
            'message' => 'success',
            'data'    => $customers
        ]);
    }

}
