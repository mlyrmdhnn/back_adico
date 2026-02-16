<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\CustomerService;
use App\Models\Customer;
use App\Models\DataCustomer;
// use App\Services\CustomerCodeService;
use App\Http\Services\CustomerCodeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'data' => Customer::with(['createdBySalesman'])->where('id', $id)->first()
        ]);
    }

    public function show()
    {
        // $this->authorize('viewAny', DataCustomer::class);
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Customer::with(['createdBySalesman'])->latest()->paginate(10)
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
        $this->authorize('delete', Customer::class);
        $customer = Customer::where('id', $request->id)->first();
        $customer->delete();
        return response()->json([
            'status' => 'ok',
            'message' => 'Success delete customer'
        ]);
    }

    public function create(Request $request, CustomerCodeService $service)
    {
        $customer = DB::transaction(function () use ($request, $service) {

            $customerCode = $service->generate($request->storeName);

            return Customer::create([
                'customer_code' => $customerCode,
                'phone' => $request->phone,
                'npwp' => $request->npwp,
                'salesman_id' => auth()->id(),
                'created_date' => now('Asia/Jakarta'),
                'address' => $request->address,
                'store_name' => $request->storeName,
                'pic' => $request->pic,
                're' => $request->re
            ]);
        });

        return response()->json([
            'status' => 'ok',
            'data' => $customer
        ], 201);
    }

    public function customerMonthly()
    {
        $now = Carbon::now();

        $customers = Customer::with(['createdBySalesman'])->where('salesman_id', auth()->user()->id)
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

    public function edit(Request $request)
    {
        $customer = Customer::where('id', $request->id)->first();
        $customer->update([
            'store_name' => $request->store_name,
            'phone' => $request->phone,
            'npwp' => $request->npwp,
            'address' => $request->address,
            'pic' => $request->pic,
            're' => $request->re
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ],201);
    }

}
