<?php

use App\Http\Controllers\API\AboutController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\HomeController;
use App\Http\Controllers\API\NavigationController;
use App\Http\Controllers\API\NewsController;
use App\Http\Controllers\API\NewsItemController;
use App\Http\Controllers\API\Redis\CategoryController as RedisCategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\SpecialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::get('categoryProductGroup', [CategoryController::class, "categoryProductGroup"])->name('categoryProductGroup');
Route::get('redis/categoryProductGroup', [RedisCategoryController::class, "categoryProductGroup"])->name('categoryProductGroupR');

Route::get('categoryProduct', [CategoryController::class, "categoryProduct"])->name('categoryProduct');
Route::get('redis/categoryProduct', [RedisCategoryController::class, "categoryProduct"])->name('categoryProductR');

Route::get('category', [CategoryController::class, "category"])->name('category');
Route::get('redis/category', [RedisCategoryController::class, "category"])->name('categoryR');

Route::get('product', [ProductController::class, "productDetail"])->name('productDetail');

Route::get('productNewest', [ProductController::class, "productNewest"])->name('productNewest');
Route::get('productPopular', [ProductController::class, "productPopular"])->name('productPopular');
Route::get('productSpecial', [ProductController::class, "productSpecial"])->name('productSpecial');
Route::get('productSpecialPage', [ProductController::class, "productSpecialPage"])->name('productSpecialPage');
//Route::get('productsRedis', [ProductController::class, "productsRedis"])->name('productsRedis');

Route::get('about', [AboutController::class, "about"])->name('about');

Route::get('home', [HomeController::class, "home"])->name('home');

Route::get('special', [SpecialController::class, "special"])->name('special');

Route::get('navigation', [NavigationController::class, "navigation"])->name('navigation');

Route::get('blogCategory', [BlogController::class, "blogCategory"])->name('blogCategory');
Route::get('blogCategoryDetail', [BlogController::class, "blogCategoryDetail"])->name('blogCategoryDetail');
Route::get('blogItemDetail', [BlogController::class, "blogItemDetail"])->name('blogItemDetail');

Route::get('news', [NewsController::class, "news"])->name('news');
Route::get('newsItemDetail', [NewsItemController::class, "newsItemDetail"])->name('newsItemDetail');

Route::get('contact', [ContactController::class, "contact"])->name('contact');


