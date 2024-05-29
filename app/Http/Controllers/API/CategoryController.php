<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CategoryController extends Controller
{
//  this function gets only one child grouped items
//    public function categoryProductGroup(Request $request)
//    {
//        $acceptLanguage = $request->header('Accept-Language');
//        $nameColumn = 'name_' . $acceptLanguage;
//        $categoryId = $request->category_id;
//        // Fetch the parent category with its children
//        $category = Category::with('children')->findOrFail($categoryId);
//
//        // Prepare the response structure
//        $response = [
//            'category' => [
//                'id' => $category->id,
//                'name' => $category->$nameColumn,
//                'description' => $category->description,
//                'parent_id' => $category->parent_id,
//                'icon' => $category->icon,
//                'active' => $category->active,
//                'children' => []
//            ],
//            'products' => [],
//        ];
//
//        // Fetch products for the parent category
//        $parentProducts = Product::where('category_id', $category->id)
//            ->paginate(10)
//            ->toArray();
//
//        $response['products'] = [
//            'items' => $parentProducts['data'],
//            'pagination' => [
//                'total' => $parentProducts['total'],
//                'per_page' => $parentProducts['per_page'],
//                'current_page' => $parentProducts['current_page'],
//                'last_page' => $parentProducts['last_page'],
//                'next_page_url' => $parentProducts['next_page_url'],
//                'prev_page_url' => $parentProducts['prev_page_url'],
//            ],
//        ];
//
//        // Fetch products for each child category and group them
//        foreach ($category->children as $child) {
//            $childProducts = Product::where('category_id', $child->id)
//                ->paginate(10)
//                ->toArray();
//
//            $response['category']['children'][] = [
//                'id' => $child->id,
//                'name' => $child->$nameColumn,
//                'description' => $child->description,
//                'parent_id' => $child->parent_id,
//                'icon' => $child->icon,
//                'active' => $child->active,
//                'products' => [
//                    'items' => $childProducts['data'],
//                    'pagination' => [
//                        'total' => $childProducts['total'],
//                        'per_page' => $childProducts['per_page'],
//                        'current_page' => $childProducts['current_page'],
//                        'last_page' => $childProducts['last_page'],
//                        'next_page_url' => $childProducts['next_page_url'],
//                        'prev_page_url' => $childProducts['prev_page_url'],
//                    ],
//                ],
//            ];
//        }
//
//        return response()->json($response);
//    }

    public function categoryProductRedisGroup(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        $nameColumn = 'name_' . $acceptLanguage;
        $categoryId = $request->category_id;

        // Cache key based on the category ID and language
        $cacheKey = "category_product_{$categoryId}_{$acceptLanguage}";

        // Attempt to retrieve the category and products from Redis
        $data = Redis::get($cacheKey);

        if ($data) {
            // If data is found in Redis, decode it
            $response = json_decode($data, true);
        } else {
            // Fetch the category and its children from the database
            $category = Category::with('children')->findOrFail($categoryId);
            $response = $this->getCategoryData($category, $nameColumn);

            // Store the data in Redis with an expiration time of 1 hour (3600 seconds)
            Redis::set($cacheKey, json_encode($response));
            Redis::expire($cacheKey, 3600);
        }

        return response()->json($response);
    }

    private function getCategoryRedisData($category, $nameColumn)
    {
        // Cache key for the category data
        $cacheKey = "category_data_{$category->id}_{$nameColumn}";

        // Attempt to retrieve the category data from Redis
        $data = Redis::get($cacheKey);

        if ($data) {
            // If data is found in Redis, decode it
            $categoryData = json_decode($data, true);
        } else {
            // Fetch products for the given category
            $products = Product::where('category_id', $category->id)
                ->paginate(10)
                ->toArray();

            // Prepare the response structure for the category
            $categoryData = [
                'id' => $category->id,
                'name' => $category->$nameColumn,
                'description' => $category->description,
                'parent_id' => $category->parent_id,
                'icon' => $category->icon,
                'active' => $category->active,
                'products' => [
                    'items' => $products['data'],
                    'pagination' => [
                        'total' => $products['total'],
                        'per_page' => $products['per_page'],
                        'current_page' => $products['current_page'],
                        'last_page' => $products['last_page'],
                        'next_page_url' => $products['next_page_url'],
                        'prev_page_url' => $products['prev_page_url'],
                    ],
                ],
                'children' => [],
            ];

            // Recursively fetch data for child categories
            foreach ($category->children as $child) {
                $categoryData['children'][] = $this->getCategoryData($child, $nameColumn);
            }

            // Store the data in Redis with an expiration time of 1 hour (3600 seconds)
            Redis::set($cacheKey, json_encode($categoryData));
            Redis::expire($cacheKey, 3600);
        }

        return $categoryData;
    }

    public function categoryProductGroup(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        $nameColumn = 'name_' . $acceptLanguage;
        $categoryId = $request->category_id;

        $category = Category::with('children')->findOrFail($categoryId);

        $response = $this->getCategoryData($category, $nameColumn);

        return response()->json($response);
    }

    private function getCategoryData($category, $nameColumn)
    {
        // Fetch products for the given category
        $products = Product::where('category_id', $category->id)
            ->paginate(10)
            ->toArray();

        // Prepare the response structure for the category
        $categoryData = [
            'id' => $category->id,
            'name' => $category->$nameColumn,
            'description' => $category->description,
            'parent_id' => $category->parent_id,
            'icon' => $category->icon,
            'active' => $category->active,
            'products' => [
                'items' => $products['data'],
                'pagination' => [
                    'total' => $products['total'],
                    'per_page' => $products['per_page'],
                    'current_page' => $products['current_page'],
                    'last_page' => $products['last_page'],
                    'next_page_url' => $products['next_page_url'],
                    'prev_page_url' => $products['prev_page_url'],
                ],
            ],
            'children' => [],
        ];

        // Recursively fetch data for child categories
        foreach ($category->children as $child) {
            $categoryData['children'][] = $this->getCategoryData($child, $nameColumn);
        }

        return $categoryData;
    }

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

        $data = Redis::get('category_r_data');

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
            Redis::set('category_r_data', json_encode($data));
        }else{
            $data = json_decode($data, true);
        }

        return response()->json($data);
    }
}
