<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

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
            return view('Product.new');
        } else {
            $request->validate([
                'prodName'  => 'required|max:50|min:3',
                'b'         => 'required|integer|max:100',
                'j'         => 'required|integer|max:100',
                'u'         => 'required|integer|max:100',
                'k'         => 'required|integer|max:1000',
                'sugar'     => 'required|integer|max:100',
                'salt'      => 'required|integer|max:100',
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
}
