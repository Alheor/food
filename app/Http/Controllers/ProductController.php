<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('Product.product');
    }
    public function new()
    {
        return view('Product.new');
    }
}
