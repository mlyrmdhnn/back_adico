<?php

namespace App\Http\Services;

use App\Models\Requests;

class InboxService {

    public function getInbox()
    {
        return Requests::with([
            'salesman:id,name,code,deleted_at',
            'store:id,name,address'
        ])
        ->select('id', 'salesman_id', 'store_id', 'status1', 'status2','created_at')
        ->latest()
        ->paginate(10);
    }



    public function search($keyword)
    {
        return Requests::with(['salesman', 'store'])
        ->where('status', 'LIKE', "%$keyword%")
        ->orWhere('uuid', 'LIKE', "%$keyword%")
        ->orWhereHas('salesman', function($s) use ($keyword) {
            $s->where('name', 'LIKE', "%$keyword%")
            ->orWhere('code', 'LIKE', "%$keyword%");
        })->orWhereHas('store', function($s) use ($keyword) {
            $s->where('name', 'LIKE', "%$keyword%")
            ->orWhere('address', 'LIKE', "%$keyword%");
        })->latest()->paginate(10);
    }

}