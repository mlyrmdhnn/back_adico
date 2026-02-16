<?php

namespace App\Http\Services;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomerCodeService
{
    public function generate(string $storeName): string
    {
        $prefix = $this->makePrefix($storeName);

        return DB::transaction(function () use ($prefix) {

            $last = Customer::where('customer_code', 'like', $prefix . '-%')
                ->lockForUpdate()
                ->orderByDesc('customer_code')
                ->value('customer_code');

            $nextNumber = 1;

            if ($last) {
                $lastNumber = (int) substr($last, strlen($prefix) + 1);
                $nextNumber = $lastNumber + 1;
            }

            return sprintf('%s-%03d', $prefix, $nextNumber);
        });
    }

    private function makePrefix(string $name): string
    {
        $ignore = ['TOKO', 'CV', 'PT', 'UD', 'TB'];

        $words = collect(explode(' ', strtoupper($name)))
            ->reject(fn ($word) => in_array($word, $ignore))
            ->values();

        if ($words->isEmpty()) {
            $words = collect([strtoupper($name)]);
        }

        return $words
            ->take(3)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }
}