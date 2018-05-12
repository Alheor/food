<?php

namespace App\Http\Controllers;

use App\Attributes;
use App\Calculators\DishCalculator;
use App\Calculators\NutritionalValueCalculator;
use App\Dish;
use App\DishCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DishController extends Controller
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    public function list(Request $request)
    {
        $search = $request->get('search');

        if (request()->has('clear')) {
            $search = null;
        }

        if(!empty($search)) {
            $dishList = Dish::where('name', 'LIKE', "%{$search}%")->orderBy('name', 'asc')->simplePaginate(30);
        }else{
            $dishList = Dish::orderBy('name', 'asc')->simplePaginate(30);
        }

        return view('Dish.dish', [
            'dishList' => $dishList,
            'search' => $search
        ]);
    }

    /**
     * @param Request $request
     * @param string $oper
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function crEd(Request $request, $oper, $copy = false)
    {
        if ($request->method() == 'GET') {
            if ($oper == 'new') {
                return view('Dish.crEd', [
                    'dish' => null,
                    'copy' => $copy
                ]);
            } else {
                $dish = Dish::where('guid', $oper)->first();

                $dish->dishCategory->id;
                //$dish->attributes->id;
                $dish->data = json_decode($dish->data,true);
                $dc = new DishCalculator(new NutritionalValueCalculator(), $dish);

                return view('Dish.crEd', [
                    'dish' => $dish,
                    'productList' => $dc->getProductData(),
                    'copy' => $copy
                ]);
            }
        } elseif ($request->method() == 'POST') {
            $data = $request->all();
            $error_message = null;
            $status = null;

            if ($oper == 'new' || $copy) {
                $dish = new Dish();
                $dish->guid = strtoupper(guid());
                $dish->user_id = Auth::id();
            } else {
                $dish = Dish::where('guid', $oper)
                    ->where('user_id', Auth::id())
                    ->first();

                if(is_null($dish)) {
                    return response()->json(
                        [
                            'guid' => $dish->guid,
                            'status' => $status,
                            'error_message' => 'Вы не можете редактировать это блюдо!'
                        ]
                    );
                }
            }

            if (!isset($data['dish_name']) || empty($data['dish_name'])) {
                $error_message = [DishCalculator::DATA_ERROR => 'Наименование не заполнено!'];
                $status = self::STATUS_ERROR;
            }

            if (!isset($data['cat_id']) || empty($data['cat_id'])) {
                $error_message = [DishCalculator::DATA_ERROR => 'Категория не выбрана!'];
                $status = self::STATUS_ERROR;
            }

            if (!isset($data['draft']) || empty($data['draft'])) {
                $error_message = [DishCalculator::DATA_ERROR => 'draft is empty'];
                $status = self::STATUS_ERROR;
            }

//            if (!isset($data['suitable_for']) || empty($data['suitable_for'])){
//                $error_message = [DishCalculator::DATA_ERROR => 'suitable_for is empty'];
//                $status = self::STATUS_ERROR;
//            }

            if($status !== null) {
                return response()->json(
                    [
                        'guid' => $dish->guid,
                        'status' => $status,
                        'error_message' => $error_message
                    ]
                );
            }

            if(is_null($dish)) {
                return abort(404);
            }

            $dish->data = $data;
            $dc = new DishCalculator(new NutritionalValueCalculator(), $dish);

            if ($dc->isValid()) {
                DB::beginTransaction();
                try {
//                    $attributes = new Attributes();
//                    $attributes->name = 'dish_purpose';
//                    $attributes->guid = strtoupper(guid());
//
//                    $attributes->food_sushka = in_array('sh', $data['suitable_for']);
//                    $attributes->food_pohudenie = in_array('ph', $data['suitable_for']);
//                    $attributes->food_podderjka = in_array('pd', $data['suitable_for']);
//                    $attributes->food_nabor_massi = in_array('nm', $data['suitable_for']);
//                    $attributes->food_cheat_meal = in_array('cm', $data['suitable_for']);

//                    $attributes->save();

                    $dish->name = trim($data['dish_name']);
                    $dish->category_id = $data['cat_id'];
//                    $dish->attribute_id = $attributes->id;
                    $dish->draft = $data['draft'] === 'true' ? 1 : 0;
                    $dish->comment = $data['comment'];
                    $dish->data = json_encode($dish->data);
                    $dish->weight_after = $data['data']['weight_after'];
                    //print_r($dish);
                    $dish->save();

                    $status = self::STATUS_SUCCESS;
                } catch (\Throwable $e) {
                    DB::rollBack();

                    $error_message = [DishCalculator::DATA_ERROR => $e->getMessage()];
                    $status = self::STATUS_ERROR;
                }

                DB::commit();
            } else {
                $error_message = $dc->getErrors();
                $status = self::STATUS_ERROR;
            }

            return response()->json(
                [
                    'guid' => $dish->guid,
                    'status' => $status,
                    'error_message' => $error_message
                ]
            );
        } else {
            abort(403);
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
