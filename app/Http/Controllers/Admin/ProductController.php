<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Models\Admin\Category;
use App\Models\Admin\Product;
use Illuminate\Http\Request;
use App\Models\Admin\ProductImage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        // app/Http/Controllers/Admin/ProductController.php


        $products = Product::with('category')->get();
        $categories = Category::all();

        return view('admin.product.index', compact('products', 'categories'));



        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('categories.id', $request->category);
            });
        }

        $products = $query->get();
        $categories = Category::all();

        return view('admin.product.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|boolean',
            'category_ids' => 'required|array',
            'images.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'variants' => 'nullable|array',
            'variants.*.sku' => 'required|string|max:100',
            'variants.*.price' => 'required|numeric',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.options' => 'nullable|array',
        ]);

        // Create the product
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        // Attach categories
        $product->categories()->attach($validated['category_ids']);

        // Upload and save images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('upload/products'), $imageName);

                $product->images()->create([
                    'image' => $imageName,
                ]);
            }
        }

        // Save product variations
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $variant) {
                $product->variations()->create([
                    'sku' => $variant['sku'],
                    'price' => $variant['price'],
                    'stock' => $variant['stock'],
                    'options' => isset($variant['options']) ? json_encode($variant['options']) : null,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Product added successfully!');
    }


    public function destroy($id)
    {
        // Find the product by ID
        $product = Product::findOrFail($id);

        // Delete associated image if exists
        foreach ($product->images as $img) {
            $imagePath = public_path('upload/products/' . $img->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            $img->delete(); // delete from DB
        }

        // Detach the categories (optional if you want to remove associations)
        $product->categories()->detach();

        // Delete the product
        $product->delete();

        return redirect()->back()->with('success', 'Product deleted successfully!');
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'status' => 'required|in:0,1',
            'category_ids' => 'required|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|integer|exists:product_variations,id',
            'variants.*.sku' => 'required|string|max:100',
            'variants.*.price' => 'required|numeric',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.options' => 'nullable|array',
        ]);

        $product = Product::findOrFail($id);

        // Update basic fields
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'status' => $validated['status'],
        ]);

        // Replace main image (optional)
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/products'), $filename);
            $product->image = $filename;
            $product->save();
        }

        // Sync categories
        $product->categories()->sync($validated['category_ids']);

        // Upload additional images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imageFile) {
                $imageName = time() . '_' . uniqid() . '.' . $imageFile->getClientOriginalExtension();

                $imageFile->move(public_path('upload/products'), $imageName);

                $product->images()->create([
                    'image' => $imageName,
                ]);
            }
        }

        // Handle product variations
        if (!empty($validated['variants'])) {
            foreach ($validated['variants'] as $variantData) {
                $variation = null;

                if (!empty($variantData['id'])) {
                    $variation = ProductVariation::find($variantData['id']);
                }

                if ($variation) {
                    // Update existing
                    $variation->update([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'options' => isset($variantData['options']) ? json_encode($variantData['options']) : null,
                    ]);
                } else {
                    // Create new
                    $product->variations()->create([
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'stock' => $variantData['stock'],
                        'options' => isset($variantData['options']) ? json_encode($variantData['options']) : null,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Product updated successfully.');
    }




    public function deleteImage($image)
    {
        $imageRecord = ProductImage::where('image', $image)->first();

        if ($imageRecord) {
            $imagePath = public_path('upload/products/' . $imageRecord->image);

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            $imageRecord->delete();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Image not found.'], 404);
    }




}

?>