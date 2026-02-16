<?php

    namespace Database\Seeders;

    use Illuminate\Database\Seeder;
    use App\Models\Brand;
    use App\Models\Uom;
    use App\Models\Configuration;
    use App\Models\Product;
    use Maatwebsite\Excel\Facades\Excel;

    class ProductImportSeeder extends Seeder
    {

        private function cleanBarcode($value): ?string
    {
        if (empty($value) || $value === '(blank)') {
            return null;
        }

        if (is_numeric($value)) {
            return number_format($value, 0, '', '');
        }

        return trim((string) $value);
    }

        public function run(): void
        {
            $rows = Excel::toCollection(null, storage_path('app/pricelist.xlsx'))[0];

            $currentBrand = null;

            foreach ($rows as $index => $row) {

                if ($index === 0) continue; // skip header saja

                if (empty($row[1])) continue; // skip kalau product kosong

                $brand = Brand::firstOrCreate([
                    'name' => trim($row[0])
                ]);

                $uom = Uom::firstOrCreate([
                    'name' => trim($row[2] ?? '-')
                ]);

                $configuration = Configuration::firstOrCreate([
                    'configuration' => trim($row[5] ?? '-')
                ]);

                Product::create([
                    'name' => trim($row[1]),
                    'brand_id' => $brand->id,
                    'configuration_id' => $configuration->id,
                    'uom_id' => $uom->id,
                    'satuan_uom' => (int) $row[3],
                    'karton' => (int) $row[4],
                    'barcode' => $this->cleanBarcode($row[6] ?? null),
                    'discount1' => 0
                ]);
            }
        }
    }