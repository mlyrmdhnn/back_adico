<?php

namespace App\Http\Controllers;

use App\Http\Services\OmsetService;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Services\RequestService;
use App\Models\OmsetSalesman;
use App\Models\PaymentMethod;
use App\Models\RequestItems;
use App\Models\Requests;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class RequestController extends Controller
{
    public function create(Request $request, RequestService $service)
    {
        Cache::forget('requests_page_1');

        $req = $service->create($request);
        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ]);
    }

    public function detailRequest($keyword)
    {
        $request =  Requests::with(['salesman', 'store'])
        ->where('status', 'LIKE', "%$keyword%")
        ->orWhereHas('salesman', function($s) use ($keyword) {
            $s->where('name', 'LIKE', "%$keyword%")
            ->orWhere('code', 'LIKE', "%$keyword%");
        })->orWhereHas('store', function($s) use ($keyword) {
            $s->where('name', 'LIKE', "%$keyword%")
            ->orWhere('address', 'LIKE', "%$keyword%");
        })->latest()->paginate(10);

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $request
        ]);
    }

    public function reject(Request $request)
    {
        $this->authorize('updateStatus', User::class);
        $reqHeader = Requests::where('id', $request->id)->first();


        // ================= MANAGER REJECT =================
        if (auth()->user()->role === 'manager') {

            // Manager hanya boleh reject sekali
            if ($reqHeader->status1 !== 'pending') {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Cannot change decision'
                ], 403);
            }

            // Manager reject TIDAK mengubah supervisor
            $reqHeader->update([
                'status1' => 'rejected',
                'manager_id' => auth()->user()->id
            ]);
            $reqHeader->update([
                'status2' => 'rejected',
            ]);
            $reqHeader->update([
                'reject_reason' => $request->rejectReason
            ]);
        }

        // ================= SUPERVISOR REJECT =================
        if (auth()->user()->role === 'supervisor') {

            // Supervisor hanya boleh reject sekali
            if ($reqHeader->status2 !== 'pending') {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Cannot change decision'
                ], 403);
            }

            $reqHeader->update([
                'status2' => 'rejected',
                'supervisor_id' => auth()->user()->id
            ]);
            $reqHeader->update([
                'reject_reason' => $request->rejectReason
            ]);
        }
        Cache::forget('requests_page_1');
        return response()->json([
            'status' => 'ok',
            'message' => 'success update status'
        ]);
    }

    public function approved(Request $request, OmsetService $service)
    {
        $this->authorize('updateStatus', User::class);
        $approved = $service->approved($request);

        return response()->json([
            'status' => 'ok',
            'message' => 'success update status'
        ]);
    }

    public function count()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Requests::where('status1', 'pending')->orWhere('status2', 'pending')->get()->count()
        ]);
    }

    public function getPaymentMethod()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => PaymentMethod::latest()->get()
        ]);
    }

    public function allSalesmanRequest()
    {
        $request =  Requests::with([
            'salesman:id,name,code,deleted_at',
            'store:id,name,address'
        ])->where('salesman:id', auth()->user()->id)
        ->select('id', 'salesman_id', 'store_id', 'status', 'created_at')
        ->latest()
        ->paginate(10);
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $request
        ]);
    }

}
