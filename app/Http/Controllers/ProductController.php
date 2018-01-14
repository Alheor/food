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

        if(!empty($search)) {
            $products = Product::where('name', 'LIKE', "%{$search}%")->orderBy('name', 'asc')->simplePaginate(30);
        }else{
            $products = Product::orderBy('name', 'asc')->simplePaginate(30);
        }

        return view('Product.product', [
            'products' => $products,
            'success' => $request->get('success'),
            'search' => $search
        ]);
    }

    /**
     * @param Request $request
     * @return \Exception|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function new(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('Product.crEd', [
                'form' => 'new_form'
            ]);
        } else {

            $validator = Validator::make($request->all(), [
                'prodName'  => 'required|max:100|min:3',
                'b'         => 'required|numeric|max:100',
                'j'         => 'required|numeric|max:100',
                'u'         => 'required|numeric|max:100',
                'cellulose' => 'required|numeric|max:100',
                'k'         => 'required|numeric|max:1000',
                'category'  => 'required|integer',
                'manufacturer' => 'required|integer',
            ]);

            $validator->after(function ($validator) {
                if (
                    false === request()->has('sh') &&
                    false === request()->has('ph') &&
                    false === request()->has('pd') &&
                    false === request()->has('nm') &&
                    false === request()->has('cm')
                ) {
                    $validator->errors()->add('field', '"Подходит для" не указано');
                }
            });
            $validator->validate();

            $exist = Product::where('name', trim($request->get('prodName')))
               ->where('manufacturer_id', $request->get('manufacturer'))
                ->first();

            $validator->after(function ($validator) use ($exist) {
                if(!is_null($exist)) {
                    $validator->errors()->add('prodName', 'Такой продукт уже существует');
                }
            });

            $validator->validate();

            DB::beginTransaction();

            try {
                $attributes = new Attributes();
                $attributes->name = 'food_purpose';
                $attributes->guid = strtoupper(guid());
                $attributes->food_sushka = request()->has('sh');
                $attributes->food_pohudenie = request()->has('ph');
                $attributes->food_podderjka = request()->has('pd');
                $attributes->food_nabor_massi = request()->has('nm');
                $attributes->food_cheat_meal = request()->has('cm');

                $attributes->save();

                $product = new Product();
                $product->attribute_id = $attributes->id;
                $product->name = trim($request->get('prodName'));
                $product->user_id = Auth::id();
                $product->guid = strtoupper(guid());
                $product->b = (float)$request->get('b');
                $product->j = (float)$request->get('j');
                $product->u = (float)$request->get('u');
                $product->cellulose = (float)$request->get('cellulose');
                $product->k = (float)$request->get('k');
                $product->sugar = request()->has('sugar');
                $product->salt = request()->has('salt');
                $product->category_id = $request->get('category');
                $product->manufacturer_id = $request->get('manufacturer');

                $product->save();
            } catch (\Throwable $e) {
                DB::rollBack();

                return new \Exception(
                    'Не удалось создать объект: ' . $e->getMessage()
                );
            }

            DB::commit();

            return redirect()->route('products', ['success' => 'new']);
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
