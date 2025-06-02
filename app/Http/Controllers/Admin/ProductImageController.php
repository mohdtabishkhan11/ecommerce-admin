<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Admin\ProductImage;

class ProductImageController extends Controller
{

    public function destroy($id)
    {
        $productImage = ProductImage::find($id);

        if (!$productImage) {
            return response()->json(['error' => 'Image not found.'], 404);
        }

        $imagePath = public_path('upload/products/' . $productImage->image);

        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        $productImage->delete();

        // Directly return a response message for debugging
        return response()->json(['message' => 'Image deleted successfully.']);
    }


}


?>