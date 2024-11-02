<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     return Product::with(['category', 'brand', 'colour', 'size', 'supplier'])->get();
    // }



    public function index()
{
    $products = Product::with(['category', 'brand', 'colour', 'size', 'supplier', 'images', 'primaryImage'])
        ->get()
        ->map(function ($product) {
            // Set the primary image URL
            if ($product->primaryImage) {
                $product->primaryImage->image_path = url('product-images/' . $product->primaryImage->image_path);
            }
            // Set URLs for all images
            $product->images = $product->images->map(function ($image) {
                $image->image_path = url('product-images/' . $image->image_path);
                return $image;
            });
            return $product;
        });

    return response()->json($products);
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
