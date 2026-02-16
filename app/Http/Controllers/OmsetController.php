<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\OmsetService;
use App\Models\OmsetSalesman;
use App\Models\SalesTargets;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;


class OmsetController extends Controller
{
    public function create(Request $request) {
        $user = User::where('role', 'salesman')->get();
        $old = SalesTargets::where('status', 'active')->get();
        foreach($old as $o) {
            $o->update([
                'status' => 'revised'
            ]);
        }
        foreach($user as $u) {
           SalesTargets::create([
            'salesman_id' => $u->id,
            'target_value' => $request->target,
            'period_type' => 'monthly',
            'period_start' => Carbon::now('Asia/Jakarta'),
            'status' => 'active',
            'created_by' => auth()->user()->id
           ]);
        }

        return response()->json([
            'status' => 'ok',
            'message' => 'success'
        ],201);
    }


    public function salesmanOmset()
    {
        $now = Carbon::now('Asia/Jakarta');
        $period = $now->copy()->startOfMonth();
        $currentDay = $now->day;

        $salesmen = User::where('role', 'salesman')
            ->with([
                'salesTarget' => function ($q) use ($period) {
                    $q->where('period_type', 'monthly')
                    ->whereMonth('period_start', $period->month)
                    ->whereYear('period_start', $period->year)
                    ->where('status', 'active')
                    ->latest('period_start');
                },
                'hariKerja' => function ($q) use ($period) {
                    $q->whereMonth('period', $period->month)
                    ->whereYear('period', $period->year);
                },
                'omset' => function ($q) use ($period) {
                    $q->whereMonth('omset_date', $period->month)
                    ->whereYear('omset_date', $period->year);
                }
            ])
            ->get()
            ->map(function ($salesman) use ($currentDay) {

                $targetRecord = $salesman->salesTarget->first();
                $target = $targetRecord?->target_value ?? 0;
                $targetId = $targetRecord?->id ?? null;

                $omset  = $salesman->omset->sum('omset_value');

                // ambil semua id hari kerja
                $hariKerjaIds = $salesman->hariKerja->pluck('id')->toArray();
                $totalHariKerja = $salesman->hariKerja->sum('day') ?: 0;

                // target harian
                $targetHarian = $totalHariKerja > 0 ? $target / $totalHariKerja : 0;

                // jumlah hari kerja yang sudah lewat bulan ini
                $hariKerjaLewat = min($currentDay, $totalHariKerja);

                // persentase per hari sampai sekarang
                $persentaseHarian = $targetHarian > 0 && $hariKerjaLewat > 0
                    ? round(($omset / ($targetHarian * $hariKerjaLewat)) * 100, 2)
                    : 0;

                return [
                    'salesman_id' => $salesman->id,
                    'salesman' => $salesman->name,
                    'salesman_code' => $salesman->code,
                    'target_id' => $targetId,
                    'target' => $target,
                    'terealisasi' => $omset,
                    'hari_kerja_ids' => $hariKerjaIds,
                    'total_hari_kerja' => $totalHariKerja,
                    'target_harian' => $targetHarian,
                    'persentase_total' => $target > 0 ? round(($omset / $target) * 100, 2) : 0,
                    'persentase_harian' => $persentaseHarian,
                ];
            });

        return response()->json([
            'status' => 'ok',
            'data' => $salesmen
        ]);
    }


public function omsetSalesman()
{
    $now = Carbon::now('Asia/Jakarta');
    $period = $now->copy()->startOfMonth();
    $currentDay = $now->day;

    $salesmen = User::where('role', 'salesman')
    ->where('id', auth()->user()->id)
        ->with([
            'salesTarget' => function ($q) use ($period) {
                $q->where('period_type', 'monthly')
                  ->whereMonth('period_start', $period->month)
                  ->whereYear('period_start', $period->year)
                  ->where('status', 'active')
                  ->latest('period_start');
            },
            'hariKerja' => function ($q) use ($period) {
                $q->whereMonth('period', $period->month)
                  ->whereYear('period', $period->year);
            },
            'omset' => function ($q) use ($period) {
                $q->whereMonth('omset_date', $period->month)
                  ->whereYear('omset_date', $period->year);
            }
        ])
        ->get()
        ->map(function ($salesman) use ($currentDay) {

            $target = $salesman->salesTarget->first()?->target_value ?? 0;
            $omset  = $salesman->omset->sum('omset_value');

            $totalHariKerja = $salesman->hariKerja->sum('day') ?: 0;

            $targetHarian = $totalHariKerja > 0 ? $target / $totalHariKerja : 0;

            $hariKerjaLewat = min($currentDay, $totalHariKerja);

            $persentaseHarian = $targetHarian > 0 && $hariKerjaLewat > 0
                ? round(($omset / ($targetHarian * $hariKerjaLewat)) * 100, 2)
                : 0;

            return [
                'salesman_id' => $salesman->id,
                'salesman' => $salesman->name,
                'salesman_code' => $salesman->code,
                'target' => $target,
                'terealisasi' => $omset,
                'total_hari_kerja' => $totalHariKerja,
                'target_harian' => $targetHarian,
                'persentase_total' => $target > 0 ? round(($omset / $target) * 100, 2) : 0,
                'persentase_harian' => $persentaseHarian,
            ];
        });

    return response()->json([
        'status' => 'ok',
        'data' => $salesmen
    ]);
}


    public function chartOmset()
    {
    $year = Carbon::now()->year;

    $months = collect(range(1, 12));

    $realized = $months->map(function ($month) use ($year) {
        return OmsetSalesman::whereYear('omset_date', $year)
            ->whereMonth('omset_date', $month)
            ->sum('omset_value');
    });

    $target = $months->map(function ($month) use ($year) {
        return SalesTargets::where('period_type', 'monthly')
            ->whereYear('period_start', $year)
            ->whereMonth('period_start', $month)
            ->where('status', 'active')
            ->sum('target_value');
    });



    return response()->json([
            'year' => $year,
            'months' => ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],
            'series' => [
                'realized' => $realized,
                'target' => $target,
            ]
        ]);
    }

    public function chartOmsetPie()
    {
        $now = Carbon::now();

        $realized = OmsetSalesman::whereYear('omset_date', $now->year)
            ->whereMonth('omset_date', $now->month)
            ->sum('omset_value');

        $target = SalesTargets::where('period_type', 'monthly')
            ->whereYear('period_start', $now->year)
            ->whereMonth('period_start', $now->month)
            ->where('status', 'active')
            ->sum('target_value');

        $remaining = max($target - $realized, 0);

        return response()->json([
            'month' => $now->translatedFormat('F Y'),
            'realized' => (float) $realized,
            'remaining' => (float) $remaining,
        ]);
    }

    public function editOmset(Request $request, OmsetService $service)
    {
    // $this->authorize('editOmset', User::class);

        $data = $request->all();
        $service->editOmset($request);

        return response()->json([
            'status' => 'ok'
        ],201);
    }

}
