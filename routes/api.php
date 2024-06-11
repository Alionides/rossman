<?php

use App\Http\Controllers\API\AboutController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\Redis\CategoryController as RedisCategoryController;
use App\Http\Controllers\API\ProductController;
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
//Route::get('productsRedis', [ProductController::class, "productsRedis"])->name('productsRedis');

Route::get('about', [AboutController::class, "about"])->name('about');
