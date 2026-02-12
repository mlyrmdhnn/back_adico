<?php

namespace App\Http\Services;

use App\Models\uom;
use App\Models\Brand;
use App\Models\Product;
use App\Models\configuration;

class ProductService{
    public function createBrand(array $data)
    {
        return Brand::create(['name' => $data['brand']]);
    }

    public function createUom(array $data)
    {
        return uom::create(['name' => $data['uom']]);
    }

    public function createConfiguration(array $data)
    {
        return configuration::create(['configuration' => $data['configuration']]);
    }

    public function create(array $data)
    {
        return Product::create([
            'name' => $data['barang'],
            'brand_id' => $data['brand'],
            'configuration_id' => $data['configuration'],
            'uom_id' => $data['uom'],
            'satuan_uom' => $data['satuanUom'],
            'karton' => $data['karton'],
            'barcode' => $data['barcode']
        ]);
    }
    public function searchProduct($keyword)
    {
        return Product::with(['brand', 'configuration', 'uom'])
    ->where(function ($q) use ($keyword) {
        $q->where('name', 'LIKE', "%$keyword%")
          ->orWhere('satuan_uom', 'LIKE', "%$keyword%")
          ->orWhere('barcode', 'LIKE', "%$keyword%")
          ->orWhereHas('brand', function ($b) use ($keyword) {
              $b->where('name', 'LIKE', "%$keyword%");
          })
          ->orWhereHas('configuration', function ($c) use ($keyword) {
              $c->where('configuration', 'LIKE', "%$keyword%");
          })
          ->orWhereHas('uom', function ($u) use ($keyword) {
              $u->where('name', 'LIKE', "%$keyword%");
          });
    })
    ->latest()
    ->paginate(10);

    }

    public function deleteProduct($id)
    {
        $product = Product::where('id', $id)->first();
        $product->delete();
    }

    public function getDetail($id)
    {
        return Product::with(['configuration', 'brand', 'uom'])->where('id', $id)->first();
    }

    public function edit($data, $id)
    {

        $product = Product::where('id', $id)->first();

        return $product->update([
            'name' => $data['barang'],
            'brand_id' => $data['brand'],
            'configuration_id' => $data['configuration'],
            'uom_id' => $data['uom'],
            'satuan_uom' => $data['satuanUom'],
            'karton' => $data['karton'],
            'discount1' => $data['discount1'],
            'barcode' => $data['barcode']
        ]);

    }
}
