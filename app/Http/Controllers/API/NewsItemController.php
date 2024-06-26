<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\NewsItem;
use Illuminate\Http\Request;

class NewsItemController extends Controller
{
//    public function newsItem(Request $request){
//
//        $acceptLanguage = $request->header('Accept-Language', 'az');
//
//        $news = NewsItem::where('active', true)->paginate(9);
//        $newsItems = $news->map(function ($item) use ($acceptLanguage) {
//            $item->image = url('uploads/' . $item->image);
//            return [
//                'id' => $item->id,
//                'seo_title' => $item['seo_title_' . $acceptLanguage],
//                'seo_desc' => $item['seo_desc_' . $acceptLanguage],
//                'title' => $item['title_' . $acceptLanguage],
//                'slug' => $item['slug_' . $acceptLanguage],
//                'text' => $item['text_' . $acceptLanguage],
//                'image' => $item->image,
//                'active' => $item->active,
//            ];
//        });
//
//        return response()->json([
//            'news_items' => $newsItems,
//            'pagination' => [
//                'total' => $news->total(),
//                'per_page' => $news->perPage(),
//                'current_page' => $news->currentPage(),
//                'last_page' => $news->lastPage(),
//                'next_page_url' => $news->nextPageUrl(),
//                'prev_page_url' => $news->previousPageUrl(),
//            ],
//        ]);
//
//    }

    public function newsItemDetail(Request $request){

        $acceptLanguage = $request->header('Accept-Language', 'az');
        $slugColumn = 'slug_' . $acceptLanguage;
        $slug = $request->input('slug');
        $slug = filter_var($slug, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $newsItem = NewsItem::where($slugColumn, $slug)->where('active', 1)->first();

        $relatedItems = NewsItem::where('id', '!=', $newsItem->id)
            ->where('active', 1)
            ->get()
            ->map(function($item) use ($acceptLanguage) {
                return [
                    'id' => $item->id,
                    'seo_title' => $item['seo_title_' . $acceptLanguage],
                    'seo_desc' => $item['seo_desc_' . $acceptLanguage],
                    'title' => $item['title_' . $acceptLanguage],
                    'slug' => $item['slug_' . $acceptLanguage],
                    'text' => $item['text_' . $acceptLanguage],
                    'image' => url('uploads/' . $item->image),
                ];
            });

        if (!$newsItem) {
            return response()->json(['error' => 'Blog Item not found'], 404);
        }

        // Modify image URL
        $newsItem->image = url('uploads/' . $newsItem->image);
        // Localize fields
        $localizedNewsItem = [
            'id' => $newsItem->id,
            'seo_title' => $newsItem['seo_title_' . $acceptLanguage],
            'seo_desc' => $newsItem['seo_desc_' . $acceptLanguage],
            'title' => $newsItem['title_' . $acceptLanguage],
//            'slug' => $blogItem['slug_' . $acceptLanguage],
            'slug_az' => $newsItem->slug_az,
            'slug_en' => $newsItem->slug_en,
            'slug_ru' => $newsItem->slug_ru,
            'text' => $newsItem['text_' . $acceptLanguage],
            'image' => $newsItem->image,
            'related_items' => $relatedItems,
        ];

        return response($localizedNewsItem);
    }
}
