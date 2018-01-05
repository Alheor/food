<?php

namespace App\Http\Controllers;

use App\Dish;
use App\DishCategory;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function list()
    {
        $dishList = Dish::orderBy('name', 'asc')->get();

        return view('Dish.dish', [
            'dishList' => $dishList
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function crEd(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('Dish.crEd', [
                'form' => 'new_form'
            ]);
        } else {

        }
    }

    public function getCategoryList(Request $request) {
        function asd($item, &$arr) {
            $item = $item->sortBy('name');
            foreach ($item as $el) {
                $tmpArray =  ['id' => $el->id,'name' => $el->name];
                if (!$el->cats->isEmpty()) {
                    asd($el->cats,  $tmpArray['children']);
                }
                $arr[] = $tmpArray;
            }
        }

        $arr = [];
        $dc = DishCategory::orderBy('name', 'asc')->get();

        foreach ($dc as $item) {
            //Элементы верхнего уровня
            if (is_null($item->parent_id)) {
                $tmpArray = ['id' => $item->id,'name' => $item->name];
                if (!$item->cats->isEmpty()) {
                    asd($item->cats, $tmpArray['children']);
                }

                $arr[] = $tmpArray;
            }
        }

        return view('Dish.categories', [
            'tplName' => 'dishCategories',
            'categories' => json_encode($arr),
            'jsguid' => $request->get('guid')
        ]);
    }
}
