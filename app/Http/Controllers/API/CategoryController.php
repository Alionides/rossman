<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CategoryController extends Controller
{

    public function categoryProduct(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        $nameColumn = 'name_' . $acceptLanguage;

        $category = Category::findOrFail($request->category_id);

        $products = Product::where('category_id', $request->category_id)
            ->paginate(10);

        $response = [
            'category' => [
                'id' => $category->id,
                'name' => $category->$nameColumn,
                'description' => $category->description,
                'parent_id' => $category->parent_id,
                'icon' => $category->icon,
                'active' => $category->active,
            ],
            'products' => $products->items(),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ]
        ];

        return response()->json($response);
    }

    public function categoryProductRedis(Request $request)
    {
//        return response($request);
        $acceptLanguage = $request->header('Accept-Language');
        $nameColumn = 'name_' . $acceptLanguage;
        $cacheKey = "category_{$request->category_id}_products";
        $data = Redis::get($cacheKey);

        if (!$data) {
            $category = Category::findOrFail($request->category_id);
            $products = Product::where('category_id', $request->category_id)
                ->paginate(10);
            $data = [
                'category' => [
                    'id' => $category->id,
                    'name' => $category->$nameColumn,
                    'description' => $category->description,
                    'parent_id' => $category->parent_id,
                    'icon' => $category->icon,
                    'active' => $category->active,
                ],
                'products' => $products->items(),
                'pagination' => [
                    'total' => $products->total(),
                    'per_page' => $products->perPage(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'next_page_url' => $products->nextPageUrl(),
                    'prev_page_url' => $products->previousPageUrl(),
                ]
            ];
            Redis::set($cacheKey, json_encode($data));
        }else {
            // Data found in Redis, decode the JSON string to an array
            $data = json_decode($data, true);
        }

        return response()->json($data);
    }

    public function category(Category $category, Request $request){
        $acceptLanguage = $request->header('Accept-Language');
        $nameColumn = 'name_' . $acceptLanguage;
        $categories = Category::all(
            'parent_id',
            'name_'.$acceptLanguage,
            'code',
            'icon',
            'active'
        );

        $data = $categories->map(function ($category) use ($nameColumn) {
            return [
                'parent_id' => $category->parent_id,
                'name' => $category->$nameColumn,
                'code' => $category->code,
                'icon' => $category->icon,
                'active' => $category->active,
            ];
        });

        return response($data);
    }

    public function categoryRedis(Category $category, Request $request){
        $acceptLanguage = $request->header('Accept-Language');

        $data = Redis::get('category_cached_data');

        if (!$data) {
            $nameColumn = 'name_' . $acceptLanguage;
            $categories = Category::all(
        'parent_id',
                'name_'.$acceptLanguage,
                'code',
                'icon',
                'active'
            );
            $data = $categories->map(function ($category) use ($nameColumn) {
                return [
                    'parent_id' => $category->parent_id,
                    'name' => $category->$nameColumn,
                    'code' => $category->code,
                    'icon' => $category->icon,
                    'active' => $category->active,
                ];
            });
            Redis::set('category_cached_data', $data);
        }

        return response($data);
    }
}
