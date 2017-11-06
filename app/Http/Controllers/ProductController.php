<?php

namespace App\Http\Controllers;

use App\Manufacturer;
use App\Product;
use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Nathanmac\GUID\Facades\GUID;

class ProductController extends Controller
{
    public function index()
    {
        return view('Product.product');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function new(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('Product.new', [
                'form' => 'new_form'
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'prodName'  => 'required|max:50|min:3',
                'b'         => 'required|numeric|max:100',
                'j'         => 'required|numeric|max:100',
                'u'         => 'required|numeric|max:100',
                'k'         => 'required|numeric|max:1000',
                'sugar'     => 'numeric|max:100',
                'salt'      => 'numeric|max:100',
                'category'  => 'required|integer',
                'manufacturer' => 'required|integer',
            ]);


                $validator->after(function ($validator) {
                    if (
                        false === request()->has('ph') &&
                        false === request()->has('pd') &&
                        false === request()->has('nm') &&
                        false === request()->has('cm')
                    ) {
                        $validator->errors()->add('field', '"Подходит для" не указано');
                    }
                });

            $validator->validate();


            $product = new Product();
            $product->name = $request->get('prodName');
            $product->user_id = Auth::id();
            $product->guid = strtoupper(guid());
            $product->b = $request->get('b');
            $product->j = $request->get('j');
            $product->u = $request->get('u');
            $product->k = $request->get('k');
            $product->sugar = $request->get('sugar');
            $product->salt = $request->get('salt');
            $product->category_id = $request->get('category');
            $product->manufacturer_id = $request->get('manufacturer');
            $product->suitable_w_loss = request()->has('ph')? 1 : 0;
            $product->suitable_w_maintenance = request()->has('pd')? 1 : 0;
            $product->suitable_w_loss_gain = request()->has('nm')? 1 : 0;
            $product->suitable_cheat_meal = request()->has('cm')? 1 : 0;

            $product->save();

            return view('Product.new', [
                'form' => 'success_form'
            ]);
        }
    }

    public function getCategoryList(Request $request) {
        function asd($item, $pc, &$arr) {
            foreach ($item as $el) {
                $tmpArray =  ['id' => $el->id,'name' => $el->name];
                if (!$el->cats->isEmpty()) {
                    asd($el->cats, $pc, $tmpArray['children']);
                }
                $arr[] = $tmpArray;
            }
        }

        $arr = [];
        $pc = ProductCategory::All();

        foreach ($pc as $item) {
            //Элементы верхнего уровня
            if (is_null($item->parent_id)) {
                $tmpArray = ['id' => $item->id,'name' => $item->name];
                if (!$item->cats->isEmpty()) {
                    asd($item->cats, $pc, $tmpArray['children']);
                }

                $arr[] = $tmpArray;
            }
        }

        return view('Product.categories', [
            'tplName' => 'productCategories',
            'categories' => json_encode($arr),
            'jsguid' => $request->get('guid')
        ]);
    }

    public function getManufacturersList(Request $request) {
        $manufacturers = Manufacturer::All();

        return view('Product.manufacturers', [
            'tplName' => 'productManufacturers',
            'manufacturers' => $manufacturers,
            'jsguid' => $request->get('guid')
        ]);
    }
}
