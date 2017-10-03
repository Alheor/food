<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index()
    {
        return view('Dish.dish');
    }

    public function category()
    {
        return view('Dish.category');
    }
}
