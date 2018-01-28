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
Route::get('/statistic', 'IndexController@statistic')->name('statistic');
Route::middleware('auth')->group(function () {
    Route::get('/performance/list', 'PerformanceController@list')->name('performance_list');
    Route::match(['get', 'post'],'/performance/{oper}', 'PerformanceController@crEd')->name('performance_cred');

    Route::get('/dishes/list', 'DishController@list')->name('dishes');
    Route::match(['get', 'post'],'/dishes/{oper}', 'DishController@crEd')->name('new_dish');
    Route::get('/dish_category', 'DishController@getCategoryList')->name('dish_category');

    Route::get('/products', 'ProductController@index')->name('products');
    Route::match(['get', 'post'], '/products/{oper?}', 'ProductController@crEd')->name('new_product');
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
        ->name('find_dp');
    Route::post('/food_diary/save_day', 'FoodDiaryController@saveDay')->name('foodDiarySaveDay');
});