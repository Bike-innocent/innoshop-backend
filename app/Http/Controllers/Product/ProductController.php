<?php

// namespace App\Http\Controllers\Product;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;
// use App\Models\Product;

// class ProductController extends Controller
// {
//     /**
//      * Display a listing of the resource.
//      */
//     // public function index()
//     // {
//     //     return Product::with(['category', 'brand', 'colour', 'size', 'supplier'])->get();
//     // }



    // public function index()
    // {
    //     $products = Product::with(['category', 'brand', 'colour', 'size', 'supplier', 'images', 'primaryImage'])
    //         ->get()
    //         ->map(function ($product) {
    //             // Set the primary image URL
    //             if ($product->primaryImage) {
    //                 $product->primaryImage->image_path = url('product-images/' . $product->primaryImage->image_path);
    //             }
    //             // Set URLs for all images
    //             $product->images = $product->images->map(function ($image) {
    //                 $image->image_path = url('product-images/' . $image->image_path);
    //                 return $image;
    //             });
    //             return $product;
    //         });

    //     return response()->json($products);
    // }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         //
//     }

//     /**
//      * Display the specified resource.
//      */
    // public function show($slug)
    // {
    //     $product = Product::where('slug', $slug)
    //         ->with(['category', 'brand', 'colour', 'size', 'supplier', 'images'])
    //         ->firstOrFail();

    //     // Set the URL for the primary image
    //     if ($product->primaryImage) {
    //         $product->primaryImage->image_path = url('product-images/' . $product->primaryImage->image_path);
    //     }

    //     // Set the URLs for all additional images
    //     $product->images = $product->images->map(function ($image) {
    //         $image->image_path = url('product-images/' . $image->image_path);
    //         return $image;
    //     });

    //     return response()->json($product);
    // }


//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, string $id)
//     {
//         //
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(string $id)
//     {
//         //
//     }
// }






namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\Colour;
use App\Models\Size;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    // Fetch all products

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

    // Fetch dependencies for the dropdowns
    public function fetchDependencies()
    {
        return response()->json([
            'categories' => ProductCategory::all(),
            'brands' => Brand::all(),
            'colours' => Colour::all(),
            'sizes' => Size::all(),
            'suppliers' => User::role('supplier')->get(),
        ]);
    }

    // Store a new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'colour_id' => 'required|exists:colours,id',
            'size_id' => 'required|exists:sizes,id',
            'supplier_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }

    // Show a single product
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with(['category', 'brand', 'colour', 'size', 'supplier', 'images'])
            ->firstOrFail();

        // Set the URL for the primary image
        if ($product->primaryImage) {
            $product->primaryImage->image_path = url('product-images/' . $product->primaryImage->image_path);
        }

        // Set the URLs for all additional images
        $product->images = $product->images->map(function ($image) {
            $image->image_path = url('product-images/' . $image->image_path);
            return $image;
        });

        return response()->json($product);
    }

    // Update a product
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
           
            'category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'colour_id' => 'required|exists:colours,id',
            'size_id' => 'required|exists:sizes,id',
            'supplier_id' => 'required|exists:users,id',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ]);

        $product->update($validated);
        return response()->json($product);
    }

    // Delete a product
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
