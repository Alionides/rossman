<?php

use App\Admin\Controllers\AboutController;
use App\Admin\Controllers\CategoryController;
use App\Admin\Controllers\NavigationController;
use App\Admin\Controllers\ProductController;
use Illuminate\Routing\Router;
use App\Admin\Controllers\BrandController;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('brands', BrandController::class);
    $router->resource('categories', CategoryController::class);
    $router->resource('products', ProductController::class);
    $router->resource('abouts', AboutController::class);
    $router->resource('navigations', NavigationController::class);

});
