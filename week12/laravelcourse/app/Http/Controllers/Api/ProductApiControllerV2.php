<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiControllerV2 extends Controller
{
    public function index(): JsonResponse
    {
        $products = ProductResource::collection(Product::all());
        return response()->json($products, 200);
    }

    public function show(string $id): JsonResponse
    {
        $product = new ProductResource(Product::findOrFail($id));
        return response()->json($product, 200);
    }

    public function save(Request $request): JsonResponse
    {
        $productDataValidated = Product::validate($request->all());
        $product = new ProductResource(Product::create($productDataValidated));
        
        return response()->json($product, 200);
    }
}
