<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Auth;

class FoodDiaryController extends Controller
{
    public function newDay()
    {

        $user = User::where('id', Auth::id())->first();

        return view('FoodDiary.newDay',[
            'numberOfMeals' => $user->number_of_meals
        ]);
    }
}
