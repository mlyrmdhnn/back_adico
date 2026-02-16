<?php

namespace App\Http\Services;

use App\Models\Customer;
// use App\Models\DataCustomer;

class CustomerService {
    public function searchCustomer($keyword)
    {
        return Customer::where('customer_code', 'LIKE', "%$keyword%}")
        ->orWhere('store_name', 'LIKE', "%$keyword%")
        ->orWhere('phone', 'LIKE', "%$keyword%")
        ->orWhere('npwp', 'LIKE', "%$keyword%")
        ->orWhere('address', 'LIKE', "%$keyword%")
        ->orWhere('pic', 'LIKE', "$keyword")
        ->orWhere('re', 'LIKE', "%$keyword%")
        ->latest()->paginate(10);
    }
}