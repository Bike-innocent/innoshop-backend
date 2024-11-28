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
        'primary_image_index' => 'required|integer|min:0',
    ]);

    $productData = collect($validated)->except(['images', 'primary_image_index'])->toArray();
    $primaryImageIndex = $validated['primary_image_index'];

    // Create the product
    $product = Product::create($productData);

    // Check if images are uploaded
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $key => $image) {
            $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('product-images'), $filename);

            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $filename,
                'is_primary' => $key == $primaryImageIndex, // Use the provided primary image index
            ]);
        }
    }

    return response()->json([
        'product' => $product,
        'message' => 'Product created successfully',
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















public function update(Request $request, $slug)
{
    $product = Product::where('slug', $slug)->firstOrFail();

    // Validate the incoming request data
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
        'primary_image_index' => 'required|integer|min:0', // Ensure primary image index is provided
    ]);

    // Update product fields except 'images'
    $productData = collect($validated)->except(['images', 'deleted_images', 'primary_image_index'])->toArray();
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

            // Save the new image in the database
            $newImage = $product->images()->create([
                'image_path' => $filename, // Store only the filename
                'is_primary' => false, // Initially set it as non-primary
            ]);
        }
    }

    // Assign primary image based on selected image index (existing or new)
    if ($request->has('primary_image_index')) {
        // Reset the current primary image to non-primary
        $product->images()->update(['is_primary' => false]);

        // Check if primary image is a new image or existing one
        $primaryImageIndex = $request->input('primary_image_index');

        // If the selected index corresponds to an existing image
        $existingImage = $product->images()->skip($primaryImageIndex)->first();
        if ($existingImage) {
            $existingImage->update(['is_primary' => true]);
        } else {
            // If it's a new image, set the primary image based on index
            $newImage = $product->images()->orderBy('created_at', 'desc')->first(); // Get the most recently uploaded image
            $newImage->update(['is_primary' => true]);
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
