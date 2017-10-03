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

Route::get('/plan', 'PlanController@index')->name('plan');

Route::get('/dishes', 'DishController@index')->name('dishes');
Route::get('/dishes_category', 'DishController@category')->name('dishes_category');

Route::get('/products', 'ProductController@index')->name('products');
Route::get('/products_category', 'ProductController@category')->name('products_category');