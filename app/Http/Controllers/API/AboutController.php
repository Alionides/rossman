<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function about(Request $request){
        $acceptLanguage = $request->header('Accept-Language');
        $nameColumn = 'name_' . $acceptLanguage;
        $slugColumn = 'slug_' . $acceptLanguage;
        $textColumn = 'text_' . $acceptLanguage;

        $slug = $request->input('slug');
        $slug = filter_var($slug, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        $data = About::where($slugColumn, $slug)->firstOrFail();
        $data->sec_1_image = $data->sec_1_image ? url('uploads/' . $data->sec_1_image) : null;
        $data->sec_2_image = $data->sec_2_image ? url('uploads/' . $data->sec_2_image) : null;
        $data->sec_3_image = $data->sec_3_image ? url('uploads/' . $data->sec_3_image) : null;
        $data->banner_image = $data->banner_image ? url('uploads/' . $data->banner_image) : null;
        $data->vision_1_image = $data->vision_1_image ? url('uploads/' . $data->vision_1_image) : null;
        $data->vision_2_image = $data->vision_2_image ? url('uploads/' . $data->vision_2_image) : null;

        $sliderImages = $data->slider;
        if (is_array($sliderImages)) {
            $sliderImages = array_map(function($image) {
                return url('uploads/images/' . $image);
            }, $sliderImages);

            $data->slider = $sliderImages;
        }

        return response($data);
    }
}
