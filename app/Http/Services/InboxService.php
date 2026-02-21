<?php

namespace App\Http\Services;

use App\Models\Requests;

class InboxService {





    public function search($keyword)
    {
        return Requests::with(['salesman', 'store'])
        ->where('status', 'LIKE', "%$keyword%")
        ->orWhere('created_at', 'LIKE', "%$keyword%")
        ->orWhereHas('salesman', function($s) use ($keyword) {
            $s->where('name', 'LIKE', "%$keyword%")
            ->orWhere('code', 'LIKE', "%$keyword%");
        })->orWhereHas('store', function($s) use ($keyword) {
            $s->where('name', 'LIKE', "%$keyword%")
            ->orWhere('address', 'LIKE', "%$keyword%");
        })->latest()->paginate(10);
    }

}