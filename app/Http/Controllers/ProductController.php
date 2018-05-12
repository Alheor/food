<?php

namespace App\Http\Controllers;

use App\Attributes;
use App\Manufacturer;
use App\Product;
use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Nathanmac\GUID\Facades\GUID;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');

        if (request()->has('clear')) {
            $search = null;
        }

        if (!empty($search)) {
            $products = Product::where('name', 'LIKE', "%{$search}%")
                ->orderBy('name', 'asc')
                ->simplePaginate(30);
        } else {
            $products = Product::orderBy('name', 'asc')
                ->simplePaginate(30);
        }

        return view('Product.product', [
            'products' => $products,
            'success' => $request->get('success'),
            'search' => $search
        ]);
    }

    /**
     * @param Request $request
     * @param $oper
     * @param bool $copy
     * @return \Exception|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function crEd(Request $request, $oper)
    {
        $copy = ($request->get('copy') === '1');

        if ($request->method() == 'GET') {
            if ($oper === 'new') {
                return view('Product.crEd', [
                    'form' => 'new_form'
                ]);
            } else {
                $product = Product::where('guid', $oper)->first();

                if (is_null($product)) {
                    abort(404);
                }

//                $product->attributes->id;
                return view('Product.crEd', [
                    'form' => 'new_form',
                    'product' => $product,
                    'copy' => $copy
                ]);
            }
        }

        if ($request->method() == 'POST') {
            $messages = [
                'prodName.required'  => 'Поле "Наименование " не заполнено',
                'b.required' => 'Поле "Белки" не заполнено',
                'j.required' => 'Поле "Жиры" не заполнено',
                'u.required' => 'Поле "Углеводы" не заполнено',
                'cellulose.required' => 'Поле "Клетчатка" не заполнено',
                'k.required' => 'Поле "Ккал" не заполнено',
                //'category.required' => 'Категория не выбрана',
                'manufacturer.required' => 'Торговая марка не выбрана',

                'prodName.min'  => 'Поле "Наименование " слишком короткое (мин. 2 символа)',
                'prodName.max'  => 'Поле "Наименование " слишком длинное (макс. 100 символов)',
                'b.min' => 'Значение поля "Белки" слишком маленькое (мин. 0)',
                'b.max' => 'Значение поля "Белки" слишком большое (макс. 100)',
                'j.max' => 'Значение поля  "Жиры" слишком большое (макс. 100)',
                'j.min' => 'Значение поля "Жиры" слишком маленькое (мин. 0)',
                'u.max' => 'Значение поля  "Углеводы" слишком большое (макс. 100)',
                'u.min' => 'Значение поля "Углеводы" слишком маленькое (мин. 0)',
                'cellulose.max' => 'Значение поля  "Клетчатка" большое (макс. 100)',
                'cellulose.min' => 'Значение поля "Клетчатка" слишком маленькое (мин. 0)',
                'k.max' => 'Значение поля  "Ккал" слишком большое (макс. 1000)',
                'k.min' => 'Значение поля "Ккал" слишком маленькое (мин. 0)',

                'b.numeric' => 'Поле "Белки" должно быть числом.',
                'j.numeric' => 'Поле "Жиры" должно быть числом.',
                'u.numeric' => 'Поле "Углеводы" должно быть числом.',
                'cellulose.numeric' => 'Поле "Клетчатка" должно быть числом.',
                'k.numeric' => 'Поле "Ккал" должно быть числом.'
            ];

            $validator = Validator::make($request->all(), [
                'prodName'  => 'required|min:2|max:100',
                'b'         => 'required|numeric|min:0|max:100',
                'j'         => 'required|numeric|min:0|max:100',
                'u'         => 'required|numeric|min:0|max:100',
                'cellulose' => 'required|numeric|min:0|max:100',
                'k'         => 'required|numeric|min:0|max:1000',
                //'category'  => 'required|integer',
                'manufacturer' => 'required|integer',
            ], $messages);

//            $validator->after(function ($validator) {
//                if (
//                    false === request()->has('sh') &&
//                    false === request()->has('ph') &&
//                    false === request()->has('pd') &&
//                    false === request()->has('nm') &&
//                    false === request()->has('cm')
//                ) {
//                    $validator->errors()->add('field', '"Подходит для" не указано');
//                }
//            });
            $validator->validate();

            if ($oper === 'new' || $copy) {
                $exist = Product::where('name', trim($request->get('prodName')))
                    ->where('manufacturer_id', $request->get('manufacturer'))
                    ->first();

                $validator->after(function ($validator) use ($exist) {
                    if (!is_null($exist)) {
                        $validator->errors()->add('prodName', 'Такой продукт уже существует');
                    }
                });

                $validator->validate();

                $product = new Product();
                $product->user_id = Auth::id();
                $product->guid = strtoupper(guid());

//                $attributes = new Attributes();
//                $attributes->guid = strtoupper(guid());
//                $attributes->name = 'food_purpose';
            } else {
                $product = Product::where('guid', $oper)
                    ->where('user_id', Auth::id())
                    ->first();

                if (is_null($product)) {
                    return abort(403, 'Access Denied');
                }

//                $attributes = $product->attributes;
            }

            DB::beginTransaction();

            try {
//                $attributes->food_sushka = request()->has('sh');
//                $attributes->food_pohudenie = request()->has('ph');
//                $attributes->food_podderjka = request()->has('pd');
//                $attributes->food_nabor_massi = request()->has('nm');
//                $attributes->food_cheat_meal = request()->has('cm');

//                $attributes->save();

//                $product->attribute_id = $attributes->id;
                $product->name = trim($request->get('prodName'));

                $product->b = (float)$request->get('b');
                $product->j = (float)$request->get('j');
                $product->u = (float)$request->get('u');
                $product->cellulose = (float)$request->get('cellulose');
                $product->k = (float)$request->get('k');
//                $product->sugar = request()->has('sugar');
//                $product->salt = request()->has('salt');
                $product->category_id = 18;//$request->get('category');
                $product->manufacturer_id = $request->get('manufacturer');

                $product->save();
            } catch (\Throwable $e) {
                DB::rollBack();

                return new \Exception(
                    'Не удалось создать объект: ' . $e->getMessage()
                );
            }

            DB::commit();

            return redirect()->route('products', ['success' => true]);
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
        $pc = ProductCategory::orderBy('name', 'asc')->get();

        foreach ($pc as $item) {
            //Элементы верхнего уровня
            if (is_null($item->parent_id)) {
                $tmpArray = ['id' => $item->id,'name' => $item->name];
                if (!$item->cats->isEmpty()) {
                    asd($item->cats, $tmpArray['children']);
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
