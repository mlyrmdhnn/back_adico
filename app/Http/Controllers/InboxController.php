<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\InboxService;
use App\Http\Services\RequestService;
use App\Models\RequestItems;
use App\Models\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class InboxController extends Controller
{
    public function getInbox()
    {


        $inbox = Cache::remember(
            'requests_page_1',
            60,
            function () {
                return Requests::with([
                    'salesman:id,name,code,deleted_at',
                    'customer:id,store_name,address'
                ])->latest()->paginate(10);
            }
        );

        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $inbox
        ]);
    }

    public function search($keyword, InboxService $service)
    {
        $inbox = $service->search($keyword);
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $inbox
        ]);
    }

    public function detail($id)
    {
        $inbox = Requests::
        with([
            'manager',
            'supervisor',
            'paymentMethod',
            'salesman',
            'customer',
            'requestItems.product.brand',
            'requestItems.product.uom',
            'requestItems.product.configuration'
        ])
        ->findOrFail($id);


        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => $inbox
        ]);
    }
}
