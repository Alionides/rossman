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
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_category_id')->default(0);

            $table->string('seo_title_az')->nullable();
            $table->text('seo_desc_az')->nullable();

            $table->string('seo_title_en')->nullable();
            $table->text('seo_desc_en')->nullable();

            $table->string('seo_title_ru')->nullable();
            $table->text('seo_desc_ru')->nullable();

            $table->text('title_az')->nullable();
            $table->text('slug_az')->nullable();
            $table->text('text_az')->nullable();

            $table->text('title_en')->nullable();
            $table->text('slug_en')->nullable();
            $table->text('text_en')->nullable();

            $table->text('title_ru')->nullable();
            $table->text('slug_ru')->nullable();
            $table->text('text_ru')->nullable();

            $table->text('image')->nullable();

            $table->string('banner_title_az')->nullable();
            $table->string('banner_title_en')->nullable();
            $table->string('banner_title_ru')->nullable();
            $table->text('banner_desc_az')->nullable();
            $table->text('banner_desc_en')->nullable();
            $table->text('banner_desc_ru')->nullable();
            $table->text('banner_image')->nullable();
            $table->string('banner_link')->nullable();

            $table->boolean('active')->default(1);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
