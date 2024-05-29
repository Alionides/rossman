<?php

use App\Http\Controllers\API\CategoryController;
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
Route::get('categoryProductRedisGroup', [CategoryController::class, "categoryProductRedisGroup"])->name('categoryProductRedisGroup');

Route::get('categoryProduct', [CategoryController::class, "categoryProduct"])->name('categoryProduct');
Route::get('categoryProductRedis', [CategoryController::class, "categoryProductRedis"])->name('categoryProductRedis');

Route::get('category', [CategoryController::class, "category"])->name('category');
Route::get('categoryRedis', [CategoryController::class, "categoryRedis"])->name('categoryRedis');

Route::get('products', [ProductController::class, "products"])->name('products');
Route::get('productsRedis', [ProductController::class, "productsRedis"])->name('productsRedis');
