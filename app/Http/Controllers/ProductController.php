<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Services\ProductService;
use App\Models\Brand;
use App\Models\Configuration as ModelsConfiguration;
use App\Models\Configuration;
use App\Models\Product;
// use App\Models\uom;
use App\Models\Uom;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function all()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Product::with(['configuration', 'uom', 'brand'])->latest()->get()
        ]);
    }

    public function createBrand(Request $request, ProductService $service)
    {
        $this->authorize('create', Brand::class);
        $credentials = $request->validate(['brand' => 'required']);
        $service->createBrand($credentials);
        return response()->json([
            'status' => 'ok',
            'message' => 'Success create brand'
        ],201);
    }

    public function createUom(Request $request, ProductService $service)
    {
        $this->authorize('create', uom::class);
        $credentials = $request->validate(['uom' => 'required']);
        $service->createUom($credentials);

        return response()->json([
            'status' => 'ok',
            'message' => 'Success create uom'
        ],201);
    }

    public function createConfiguration(Request $request, ProductService $service)
    {
        $this->authorize('create', Configuration::class);
        $credentials = $request->validate(['configuration' => 'required']);
        $service->createConfiguration($credentials);

        return response()->json([
            'status' => 'ok',
            'message' => 'Success create configuration'
        ],201);
    }

    public function getBrand()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Brand::latest()->get()
        ]);
    }
    public function getUom()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Uom::latest()->get()
        ]);
    }
    public function getConfiguration()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => configuration::latest()->get()
        ]);
    }
    public function create(Request $request, ProductService $service)
    {
        $this->authorize('create', Product::class);
        $credentials = $request->validate([
            'brand' => 'required|numeric',
            'barang' => 'required',
            'uom' => 'required|numeric',
            'satuanUom' => 'required',
            'karton' =>'required',
            'configuration' => 'required|numeric',
            'barcode' => 'required'
        ]);
        $product = $service->create($credentials);

        return response()->json([
            'status' => 'ok',
            'message' => 'Success created product',
            'data' => $product
        ],201);
    }

    public function getProduct()
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'success',
            'data' => Product::with(['brand', 'configuration', 'uom'])->latest()->paginate(10)
        ]);
    }

    public function searchProduct($keyword, ProductService $service)
    {
        $response = $service->searchProduct($keyword);
        return response()->json([
            'status' => 'ok',
            'message' => 'Success',
            'data' => $response
        ]);
    }

    public function delete(Request $request, ProductService $service)
    {
        $this->authorize('delete', Product::class);
        $service->deleteProduct($request->id);
        return response()->json([
            'status' => 'ok',
            'message' => 'Success to delete'
        ]);
    }

    public function detail($id, ProductService $service)
    {
        $product = $service->getDetail($id);
        if(!$product) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Product not found'
            ],404);
        }
        return response()->json([
            'status' => 'ok',
            'msg' => 'success',
            'data' => $product
        ]);
    }

    public function edit(Request $request, ProductService $service)
    {
        $this->authorize('update', Product::class);
        $credentials = $request->validate([
            'brand' => 'required|numeric',
            'barang' => 'required',
            'uom' => 'required|numeric',
            'satuanUom' => 'required',
            'karton' =>'required',
            'configuration' => 'required|numeric',
            'discount1' => 'required',
            'barcode' => 'required',
        ]);
        $product = $service->edit($credentials, $request->id);
        return response()->json([
            'status' => 'ok',
            'message' => 'Success edit product',
            'data' => $product
        ],202);
    }

    public function editUom(Request $request)
    {
        $uom = Uom::where('id', $request->id)->first();
        $uom->update([
            'name' => $request->name
        ]);
    }

    public function editBrand(Request $request)
    {
        $brand = Brand::where('id', $request->id)->first();
        $brand->update([
            'name' => $request->name
        ]);
    }

    public function editConfiguration(Request $request)
    {
        $configuration = ModelsConfiguration::where('id', $request->id)->first();
        $configuration->update([
            'configuration' => $request->configuration
        ]);
    }
}
