<?php

namespace App\Http\Services;

use App\Models\Customer;
use App\Models\DataCustomer;

class CustomerService {
    public function searchCustomer($keyword)
    {
        return Customer::with(['store'])->where(function($c) use ($keyword) {
            $c->where('npwp', 'LIKE', "%$keyword%")
            ->orWhere('phone' ,'LIKE', "%$keyword%")
            ->orWhereHas('store', function($s) use ($keyword) {
                $s->where('name', 'LIKE', "%$keyword%")
                ->orWhere('address', 'LIKE', "%$keyword%");
            } );
        })->latest()->paginate(10);
    }
}