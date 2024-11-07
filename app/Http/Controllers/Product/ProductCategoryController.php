<?php

namespace App\Http\Controllers\Product;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return ProductCategory::all();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category = ProductCategory::create($validated);
        return response()->json($category, 201);
    }

    public function show(ProductCategory $productCategory)
    {
        return $productCategory;
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $productCategory->update($validated);
        return response()->json($productCategory);
    }

    public function destroy(ProductCategory $productCategory)
    {
        $productCategory->delete();
        return response()->json(null, 204);
    }

    public function restore($id)
{
    $category = ProductCategory::withTrashed()->find($id);

    if ($category) {
        $category->restore();
        return response()->json(['message' => 'Category restored successfully']);
    } else {
        return response()->json(['message' => 'Category not found'], 404);
    }
}

}
