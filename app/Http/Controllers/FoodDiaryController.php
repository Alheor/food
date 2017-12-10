<?php

namespace App\Http\Controllers;

use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodDiaryController extends Controller
{
    public function newDay()
    {
        $user = User::where('id', Auth::id())->first();

        $mealList = explode(',', $user->mealList);
        return view('FoodDiary.newDay', [
            'mealList' => $mealList
        ]);
    }

    public function findDp(Request $request)
    {
        if ($request->method() == 'GET') {
            return view('FoodDiary.DPAddition', [
                'oper' => 'get_form'
            ]);
        }

        if ($request->method() == 'POST') {
            $search = trim($request->get('search'));

            $id = $request->get('id');

            if (is_null($id)) {
                $productList = Product::where('name', 'LIKE', "%{$search}%")
                    ->orderBy('name', 'asc')
                    ->take(10)
                    ->get();

                return view('FoodDiary.DPAddition', [
                    'oper' => 'search_form',
                    'productList' => $productList
                ]);
            } else {
                return response()->json(
                    Product::where('id', $id)->first()
                );
            }
        }
    }
}
