<?php

namespace App\Http\Services;

use App\Models\RequestItems;
use App\Models\Requests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RequestService {


    public function create($data)
    {

        return DB::transaction(function() use ($data) {
            $uuid = (string) Str::uuid();

            $requestHeader = Requests::create([
                'uuid' => $uuid,
                'salesman_id' => auth()->user()->id,
                'customer_id' => $data->customer_id,
                'payment_method_id' => $data->payment_method_id,
                'status1' => 'pending',
                'status2' => 'pending',
                'message' => $data->note
            ]);

            $items = collect($data->items)->map(function ($item) use ($requestHeader) {
                return [
                    'request_id' => $requestHeader->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'uom_id' => $item['uom_id'],
                    'discount2' => $item['discount2'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            })->toArray();

            RequestItems::insert($items);

        });

    }

}