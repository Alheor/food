<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

Route::get('logout', function(){
    Auth::logout();
    return redirect('/');
});

Route::get('/', 'IndexController@index')->name('index');
Route::get('/home', 'IndexController@index');
Route::middleware('auth')->group(function () {
    Route::get('/statistic', 'IndexController@statistic')->name('statistic');

    Route::get('/plan', 'PlanController@index')->name('plan');

    Route::get('/dishes', 'DishController@index')->name('dishes');
    Route::get('/dishes/new', 'DishController@new')->name('new_dish');
    Route::get('/dishes_category', 'DishController@category')->name('dishes_category');

    Route::get('/products', 'ProductController@index')->name('products');
    Route::match(['get', 'post'], '/products/new', 'ProductController@new')->name('new_product');
    Route::get('/products_category', 'ProductController@getCategoryList')->name('products_category');
    Route::get('/products_manufacturers', 'ManufacturerController@getManufacturersList')->name('products_manufacturers');

    Route::match(['get', 'post'], '/addManufacturer', 'ManufacturerController@addManufacturer')->name('addManufacturers');

    Route::get('/food_diary/list', 'FoodDiaryController@list')->name('foodDiaryList');
    Route::match(['get', 'post'], '/food_diary/new_day', 'FoodDiaryController@newDay')->name('foodDiaryNewDay');
    Route::match(['get', 'post'], '/food_diary/load_day/{token?}', 'FoodDiaryController@loadDay')->name('foodDiaryLoadDay');
    Route::match(
        ['get', 'post'],
        '/food_diary/finddp/{id?}',
        'FoodDiaryController@findDishesOrProduct')
        ->name('find_dp')
        ->where('id', '[0-9]+');
    Route::post('/food_diary/save_day', 'FoodDiaryController@saveDay')->name('foodDiarySaveDay');
});