<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ProductController extends Controller
{

    public function productDetail(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        $nameColumn = 'name_' . $acceptLanguage;
        $slugColumn = 'slug_' . $acceptLanguage;
        $textColumn = 'text_' . $acceptLanguage;

        $slug = $request->input('slug');
        $slug = filter_var($slug, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $product = Product::where($slugColumn, $slug)->firstOrFail();

        $images = $product->images;
        if (is_array($images)) {
            $images = array_map(function($image) {
                return url('uploads/' . $image);
            }, $images);

            $product->images = $images;
        }
        $productDetails = [
            'id' => $product->id,
            'category_id' => $product->category_id,
            'code' => $product->code,
            'barcode' => $product->barcode,
            'listPrice' => $product->listPrice,
            'salePrice' => $product->salePrice,
            'name' => $product->$nameColumn,
//            'slug' => $product->$slugColumn,
            'slug_az' => $product->slug_az,
            'slug_en' => $product->slug_en,
            'slug_ru' => $product->slug_ru,
            'text' => $product->$textColumn,
            'images' => $product->images,
            'markCode' => $product->markCode,
            'markName' => $product->markName,
            'active' => $product->active,
            'created_at' => $product->created_at,
            'updated_at' => $product->updated_at,
        ];

        return response($productDetails);
    }

}
