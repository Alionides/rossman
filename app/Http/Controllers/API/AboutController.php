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
        $sections = [];
        $sections[0]= [
            'sec_1_name_az' => $data->sec_1_name_az,
            'sec_1_name_en' => $data->sec_1_name_en,
            'sec_1_name_ru' => $data->sec_1_name_ru,
            'sec_1_title_az' => $data->sec_1_title_az,
            'sec_1_title_en' => $data->sec_1_title_en,
            'sec_1_title_ru' => $data->sec_1_title_ru,
            'sec_1_desc_az' => $data->sec_1_desc_az,
            'sec_1_desc_en' => $data->sec_1_desc_en,
            'sec_1_desc_ru' => $data->sec_1_desc_ru,
            'sec_1_image' => $data->sec_1_image,
        ];
        $sections[1]= [
            'sec_2_name_az' => $data->sec_2_name_az,
            'sec_2_name_en' => $data->sec_2_name_en,
            'sec_2_name_ru' => $data->sec_2_name_ru,
            'sec_2_title_az' => $data->sec_2_title_az,
            'sec_2_title_en' => $data->sec_2_title_en,
            'sec_2_title_ru' => $data->sec_2_title_ru,
            'sec_2_desc_az' => $data->sec_2_desc_az,
            'sec_2_desc_en' => $data->sec_2_desc_en,
            'sec_2_desc_ru' => $data->sec_2_desc_ru,
            'sec_2_image' => $data->sec_2_image
        ];
        $sections[2]= [
            'sec_3_name_az' => $data->sec_3_name_az,
            'sec_3_name_en' => $data->sec_3_name_en,
            'sec_3_name_ru' => $data->sec_3_name_ru,
            'sec_3_title_az' => $data->sec_3_title_az,
            'sec_3_title_en' => $data->sec_3_title_en,
            'sec_3_title_ru' => $data->sec_3_title_ru,
            'sec_3_desc_az' => $data->sec_3_desc_az,
            'sec_3_desc_en' => $data->sec_3_desc_en,
            'sec_3_desc_ru' => $data->sec_3_desc_ru,
            'sec_3_image' => $data->sec_3_image,
        ];
        $data['sections'] = $sections;
        $data->banner_image = $data->banner_image ? url('uploads/' . $data->banner_image) : null;
        $data->vision_1_image = $data->vision_1_image ? url('uploads/' . $data->vision_1_image) : null;
        $data->vision_2_image = $data->vision_2_image ? url('uploads/' . $data->vision_2_image) : null;

        $sliderImages = $data->slider;
        if (is_array($sliderImages)) {
            $sliderImages = array_map(function($image) {
                return url('uploads/' . $image);
            }, $sliderImages);

            $data->slider = $sliderImages;
        }

        return response($data);
    }
}
