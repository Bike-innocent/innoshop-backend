<?php




namespace App\Http\Controllers\Product;

use App\Models\Product;
use App\Models\ProductImage;

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





public function store(Request $request)
{
    // Validate the input
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
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    // Remove 'images' from validated data since it doesn't belong in the products table
    $productData = collect($validated)->except(['images'])->toArray();

    // Create the product
    $product = Product::create($productData);

    // Check if images are uploaded
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $key => $image) {
            // Generate a unique filename with timestamp and original extension
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move the image to 'public/product-images' folder
            $image->move(public_path('product-images'), $filename);

            // Save only the generated filename in the database
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename, // Store only the filename
                'is_primary' => $key === 0, // Set the first image as primary
            ]);
        }
    }



    return response()->json([
        'product' => $product,
        'message' => 'Product created successfully'
    ], 201);
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





















//     // Update a product

// public function update(Request $request, $slug)
// {
//     // Fetch the product by slug
//     $product = Product::where('slug', $slug)->firstOrFail();

//     // Validate the input
//     $validated = $request->validate([
//         'name' => 'required|string|max:255',
//         'category_id' => 'required|exists:product_categories,id',
//         'brand_id' => 'nullable|exists:brands,id',
//         'colour_id' => 'required|exists:colours,id',
//         'size_id' => 'required|exists:sizes,id',
//         'supplier_id' => 'required|exists:users,id',
//         'description' => 'required|string',
//         'price' => 'required|numeric',
//         'stock_quantity' => 'required|integer',
//         'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
//         'deleted_images' => 'array', // Array of image IDs to be deleted
//     ]);

//     // Update the product details
//     $product->update($validated);

//     // Handle the deletion of existing images
//     if ($request->has('deleted_images')) {
//         foreach ($request->deleted_images as $imageId) {
//             $image = ProductImage::find($imageId);
//             if ($image) {
//                 // Delete the image file from storage
//                 $imagePath = public_path('product-images/' . $image->image_path);
//                 if (file_exists($imagePath)) {
//                     unlink($imagePath);
//                 }
//                 // Delete the record from the database
//                 $image->delete();
//             }
//         }
//     }

//     // Check if new images are uploaded
//     if ($request->hasFile('images')) {
//         foreach ($request->file('images') as $key => $image) {
//             // Generate a unique filename with timestamp and original extension
//             $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

//             // Move the image to 'public/product-images' folder
//             $image->move(public_path('product-images'), $filename);

//             // Save the image in the database
//             ProductImage::create([
//                 'product_id' => $product->id,
//                 'image_path' => $filename,
//                 'is_primary' => $key === 0, // Set the first image as primary if no primary image exists
//             ]);
//         }
//     }

//     return response()->json([
//         'product' => $product,
//         'message' => 'Product updated successfully'
//     ], 200);
// }




public function update(Request $request, $slug)
{
    $product = Product::where('slug', $slug)->firstOrFail();

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
        'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        'deleted_images' => 'array', // Array of image IDs to be deleted
    ]);

    // Update product fields except 'images'
    $productData = collect($validated)->except(['images', 'deleted_images'])->toArray();
    $product->update($productData);

    // Handle deleted images
    if ($request->has('deleted_images')) {
        $deletedImageIds = $request->input('deleted_images');
        $product->images()->whereIn('id', $deletedImageIds)->delete();
    }

    // Handle new images
    if ($request->hasFile('new_images')) {
        foreach ($request->file('new_images') as $key => $image) {
            // Generate a unique filename with timestamp and original extension
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

            // Move the image to 'public/product-images' folder
            $image->move(public_path('product-images'), $filename);

            // Save only the generated filename in the database
            $product->images()->create([
                'image_path' => $filename, // Store only the filename
                'is_primary' => $key === 0, // Optionally set the first image as primary
            ]);
        }
    }

    return response()->json(['message' => 'Product updated successfully.']);
}





    // Delete a product
    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully']);
    }
}
