<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\InboxService;
use App\Http\Services\RequestService;
use App\Models\RequestItems;
use App\Models\Requests;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function getInbox(InboxService $service)
    {

        $inbox = $service->getInbox();

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
