<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class CategoryController extends Controller
{
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
        $slugColumn = 'slug_' . $acceptLanguage;
        $textColumn = 'text_' . $acceptLanguage;

        $category = Category::findOrFail($request->category_id);

        // Get all category IDs (including child categories)
        $categoryIds = $this->getAllCategoryIds($category);

        // Fetch products from all these categories
        $products = Product::whereIn('category_id', $categoryIds)->paginate(32);

        $items = $products->map(function ($product) use ($nameColumn, $slugColumn, $textColumn) {
            return [
                'id' => $product->id,
                'category_id' => $product->category_id,
                'code' => $product->code,
                'barcode' => $product->barcode,
                'listPrice' => $product->listPrice,
                'salePrice' => $product->salePrice,
                'name' => $product->$nameColumn,
                'slug' => $product->$slugColumn,
                'text' => $product->$textColumn,
                'image' => $product->image,
                'markCode' => $product->markCode,
                'markName' => $product->markName,
                'active' => $product->active,
                'created_at' => $product->created_at,
                'updated_at' => $product->updated_at,
            ];
        });

        $response = [
            'category' => $this->formatCategory($category, $nameColumn, $slugColumn),
//            'category' => [
//                'id' => $category->id,
//                'name' => $category->$nameColumn,
//                'description' => $category->description,
//                'parent_id' => $category->parent_id,
//                'icon' => $category->icon,
//                'active' => $category->active,
//            ],
            'products' => $items,
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

    private function getAllCategoryIds($category)
    {
        $categoryIds = collect([$category->id]);

        if ($category->allChildren->isNotEmpty()) {
            foreach ($category->allChildren as $childCategory) {
                $categoryIds = $categoryIds->merge($this->getAllCategoryIds($childCategory));
            }
        }

        return $categoryIds;
    }

    private function formatCategory($category, $nameColumn, $slugColumn)
    {
        return [
            'id' => $category->id,
            'name' => $category->$nameColumn,
            'description' => $category->description,
            'parent_id' => $category->parent_id,
            'icon' => $category->icon,
            'active' => $category->active,
            'subCategories' => $category->allChildren->map(function ($child) use ($nameColumn, $slugColumn) {
//                return $this->formatCategory($child, $nameColumn); this will fetch all recursively
                return [
                    'id' => $child->id,
                    'name' => $child->$nameColumn,
                    'slug' => $child->$slugColumn,
                    'description' => $child->description,
                    'parent_id' => $child->parent_id,
                    'icon' => $child->icon,
                    'active' => $child->active,
                ];
            }),
        ];
    }

    public function category(Category $category, Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language');
        $nameColumn = 'name_' . $acceptLanguage;
        $slugColumn = 'slug_' . $acceptLanguage;

        $categories = Category::with('products')->get([
            'id',
            'parent_id',
            'name_' . $acceptLanguage,
            'slug_en',
            'code',
            'icon',
            'active'
        ]);

        $categoryMap = $categories->groupBy('parent_id');

        function countProducts($category, $categoryMap)
        {
            $totalProducts = $category->products->count();
            if (isset($categoryMap[$category->id])) {
                foreach ($categoryMap[$category->id] as $child) {
                    $totalProducts += countProducts($child, $categoryMap);
                }
            }
            return $totalProducts;
        }

        $data = $categories->map(function ($category) use ($nameColumn, $slugColumn, $categoryMap) {
            return [
                'id' => $category->id,
                'parent_id' => $category->parent_id,
                'name' => $category->$nameColumn,
                'slug' => $category->$slugColumn,
                'code' => $category->code,
                'icon' => $category->icon,
                'active' => $category->active,
                'total_products' => countProducts($category, $categoryMap),
            ];
        });

        return response($data);
    }

}
