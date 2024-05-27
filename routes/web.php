<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;

Route::get('importCat', [CategoryController::class, "import"])->name('import');
Route::get('importProduct', [ProductController::class, "import"])->name('import');
Route::get('importBrand', [BrandController::class, "import"])->name('import');
