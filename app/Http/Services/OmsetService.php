<?php

namespace App\Http\Services;

use App\Models\HariKerja;
use Carbon\Carbon;
use App\Models\Requests;
use App\Models\RequestItems;
use App\Models\OmsetSalesman;
use App\Models\SalesTargets;
use Illuminate\Support\Facades\DB;

class OmsetService {
    public function approved($request)
    {
        DB::transaction(function () use ($request) {
            $reqHeader = Requests::where('id', $request->id)->first();

            if (auth()->user()->role === 'manager') {

                if ($reqHeader->status1 !== 'pending') {
                    return false;
                }

                if (in_array($reqHeader->status2, ['pending', 'rejected'])) {
                    $reqHeader->update([
                        'status2' => 'approved'
                    ]);
                }

                $reqHeader->update([
                    'status1' => 'approved',
                    'manager_id' => auth()->user()->id
                ]);
            }

            if (auth()->user()->role === 'supervisor') {

                if ($reqHeader->status2 !== 'pending') {
                    return false;
                }

                $reqHeader->update([
                    'status2' => 'approved',
                    'supervisor_id' => auth()->user()->id
                ]);
            }

            $requestItems = RequestItems::with(['product'])
                ->where('request_id', $request->id)
                ->get();

            $totalOmset = 0;

            foreach ($requestItems as $r) {
                $kartonValue = (float) $r->product->karton;
                $qty = (int) $r->qty;

                $d1 = ((float) $r->product->discount1) / 100;
                $d2 = ((float) $r->discount2) / 100;

                $base = $kartonValue * $qty;
                $subtotal = $base * (1 - $d1) * (1 - $d2);

                $totalOmset += $subtotal;
            }

            OmsetSalesman::create([
                'salesman_id' => $reqHeader->salesman_id,
                'omset_date' => Carbon::now('Asia/Jakarta'),
                'omset_value' => $totalOmset,
            ]);
        });

        return true;
    }

    public function editOmset($request)
    {
        DB::transaction(function() use ($request) {
            $targetId = $request->input('target_id');
            $targetReq = $request->input('target');
            $hariKerjaIds = $request->input('hari_kerja_ids');
            $totalHariKerja  = $request->input('total_hari_kerja');

            $target = SalesTargets::where('id', $targetId)->first();
            $target->update([
                'target_value' => $targetReq
            ]);

            $hariKerja = HariKerja::where('id', $hariKerjaIds[0])->first();
            $hariKerja->update([
                'day' => $totalHariKerja
            ]);
        });

    }
}
