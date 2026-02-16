<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;

class CustomerImportSeeder extends Seeder
{
    public function run(): void
    {
        $rows = Excel::toCollection(null, storage_path('app/customer.xlsx'))[0];

        foreach ($rows as $index => $row) {

            if ($index === 0) continue;

            if (empty($row[0]) || empty($row[1])) continue;

            $customerCode = trim((string) $row[0]); // wajib string
            $storeName    = trim($row[1]);
            $rawText      = trim($row[2] ?? '');

            $normalized = $this->normalizeMultiline($rawText);

            $phone = $this->extractPhone($normalized);
            $npwp  = $this->extractNpwp($normalized);

            $address = $this->cleanAddress($normalized, $phone, $npwp);

            Customer::updateOrCreate(
                ['customer_code' => $customerCode],
                [
                    'store_name' => $storeName,
                    'address'    => $address,
                    'phone'      => $phone,
                    'npwp'       => $npwp,
                ]
            );
        }
    }

    private function normalizeMultiline(string $text): string
    {
        $text = preg_replace("/\r\n|\r|\n/", " ", $text);
        $text = preg_replace('/\s+/', ' ', $text);
        return trim($text);
    }

    private function extractPhone(string $text): ?string
    {
        preg_match('/(\+62|62|0)[0-9]{8,13}/', $text, $matches);
        return $matches[0] ?? null;
    }

    private function extractNpwp(string $text): ?string
    {
        preg_match('/\b[0-9]{15}\b/', $text, $matches);
        return $matches[0] ?? null;
    }

    private function cleanAddress(string $text, ?string $phone, ?string $npwp): string
    {
        if ($phone) $text = str_replace($phone, '', $text);
        if ($npwp)  $text = str_replace($npwp, '', $text);

        $text = str_replace('/', '', $text);
        $text = preg_replace('/\s+/', ' ', $text);

        return trim($text);
    }
}