<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();

            $table->string('seo_title_az')->default('');
            $table->string('seo_desc_az')->default('');
            $table->string('seo_title_en')->default('');
            $table->string('seo_desc_en')->default('');
            $table->string('seo_title_ru')->default('');
            $table->string('seo_desc_ru')->default('');

            $table->string('page_name_az')->default('');
            $table->string('page_title_az')->default('');
            $table->string('page_desc_az')->default('');

            $table->string('page_name_en')->default('');
            $table->string('page_title_en')->default('');
            $table->string('page_desc_en')->default('');

            $table->string('page_name_ru')->default('');
            $table->string('page_title_ru')->default('');
            $table->string('page_desc_ru')->default('');

            $table->string('slug_az')->default('');
            $table->string('slug_en')->default('');
            $table->string('slug_ru')->default('');

//            $table->string('sec_1_name_az')->default('');
//            $table->string('sec_1_name_en')->default('');
//            $table->string('sec_1_name_ru')->default('');
//            $table->string('sec_1_title_az')->default('');
//            $table->string('sec_1_title_en')->default('');
//            $table->string('sec_1_title_ru')->default('');
//            $table->string('sec_1_desc_az')->default('');
//            $table->string('sec_1_desc_en')->default('');
//            $table->string('sec_1_desc_ru')->default('');
//            $table->string('sec_1_image')->nullable();
//
//            $table->string('sec_2_name_az')->default('');
//            $table->string('sec_2_name_en')->default('');
//            $table->string('sec_2_name_ru')->default('');
//            $table->string('sec_2_title_az')->default('');
//            $table->string('sec_2_title_en')->default('');
//            $table->string('sec_2_title_ru')->default('');
//            $table->string('sec_2_desc_az')->default('');
//            $table->string('sec_2_desc_en')->default('');
//            $table->string('sec_2_desc_ru')->default('');
//            $table->string('sec_2_image')->nullable();
//
//            $table->string('sec_3_name_az')->default('');
//            $table->string('sec_3_name_en')->default('');
//            $table->string('sec_3_name_ru')->default('');
//            $table->string('sec_3_title_az')->default('');
//            $table->string('sec_3_title_en')->default('');
//            $table->string('sec_3_title_ru')->default('');
//            $table->string('sec_3_desc_az')->default('');
//            $table->string('sec_3_desc_en')->default('');
//            $table->string('sec_3_desc_ru')->default('');
//            $table->string('sec_3_image')->nullable();

//            $table->string('statistic_count')->default('');
//            $table->string('statistic_title_az')->default('');
//            $table->string('statistic_title_en')->default('');
//            $table->string('statistic_title_ru')->default('');
//            $table->string('statistic_icon')->default('');
            $table->json('statistic')->nullable();

            $table->json('slider')->nullable();

            $table->json('vision')->nullable();

            $table->boolean('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abouts');
    }
};
