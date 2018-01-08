<?php

namespace App\Http\Controllers;

use App\Calculators\NutritionalValueCalculator;
use App\DayDiary;
use App\Diary\DayRation;
use App\Dish;
use App\Product;
use App\User;
use Faker\Provider\DateTime;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FoodDiaryController extends Controller
{
    const STATUS_SUCCESS = 'success';
    const STATUS_ERROR = 'error';

    public function newDay()
    {
        $user = User::where('id', Auth::id())->first();

        return view('FoodDiary.newDay', [
            'mealList' => json_decode($user->mealList, true),
        ]);
    }

    public function loadDay($guidOrDate = null)
    {
        if (is_null($guidOrDate)) { //Поиск последнего дня
            $day = $this->searchDayByGuidOrDate(null, (new \DateTime())->modify('midnight'));

            if (is_null($day)) {
                return $this->newDay();
            }
        } else {
            try {
                //Пытаемся найти по дате
                $day = $this->searchDayByGuidOrDate(null, (new \DateTime($guidOrDate))->modify('midnight'));
            } catch (\Throwable $t) {
                //Поиск по токену
                $day = $this->searchDayByGuidOrDate($guidOrDate);
            }
        }

        if (is_null($day)) {
            return abort(404);
        }

        $day->data = json_decode($day->data, true);

        $user = User::where('id', Auth::id())->first();
        $dr = new DayRation($day, new NutritionalValueCalculator());

        if (!$dr->isValid()) {
            abort(500);
        }

        return view('FoodDiary.newDay', [
            'mealList' => json_decode($user->mealList, true),
            'day' => $day,
            'data' => $dr->getProductData()
        ]);
    }

    /**
     * @param null $guid
     * @param \DateTime|null $date
     * @return null
     */
    private function searchDayByGuidOrDate($guid = null, \DateTime $date = null)
    {
        if (!is_null($guid)) {
            return DayDiary::where('guid', $guid)
                ->where('user_id', Auth::id())
                ->first();
        } elseif (!is_null($date)) {
            return DayDiary::where('to_date', $date)
                ->where('user_id', Auth::id())
                ->first();
        } else {
            return null;
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function findDishesOrProduct(Request $request)
    {
        $type = $request->get('type') ?? 'all';

        if ($request->method() == 'POST') {
            $search = trim($request->get('search'));

            $id = $request->get('id');

            if (is_null($id)) {
                if($type === 'product') {
                    $objList = Product::where('name', 'LIKE', "%{$search}%")
                        ->orderBy('name', 'asc')
                        ->take(10)
                        ->get();
                } else if($type === 'dish') {
                    $objList = Dish::where('name', 'LIKE', "%{$search}%")
                        ->orderBy('name', 'asc')
                        ->take(10)
                        ->get();
                } else {
                    /** @var Collection $objList */
                    $objList = Product::where('name', 'LIKE', "%{$search}%")
                        ->orderBy('name', 'asc')
                        ->take(10)
                        ->get();
                    $objList2 = Dish::where('name', 'LIKE', "%{$search}%")
                        ->orderBy('name', 'asc')
                        ->take(10)
                        ->get();

                    $objList->merge($objList2);
                }

                return view('FoodDiary.DPAddition', [
                    'oper' => 'search_form',
                    'productList' => $objList
                ]);
            } else {
                if ($type === 'product') {
                    $obj = Product::where('id', $id)->first();
                    $obj->manufacturer->name; //LAZYLOAD что бы загрузился производитель
                } else if($type === 'dish') {
                    $obj = Dish::where('id', $id)->first();
                } else {
                    $obj = Product::where('id', $id)->first();

                    if (!is_null($obj)) {
                        $obj->manufacturer->name;
                    } else {
                        $obj = Dish::where('id', $id)->first();
                    }
                }

                return response()->json(
                    $obj
                );
            }
        }

        return view('FoodDiary.DPAddition', [
            'oper' => 'get_form',
            'type' => $type
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveDay(Request $request)
    {
        $data = $request->get('data');
        $guid = $request->get('guid');
        $toDate = $request->get('to_date');

        try {
            $toDate = (new \DateTime($toDate))->modify('midnight');
        } catch (\Throwable $t) {
            abort(500, "Date {$toDate} not recognized");
        }

        $day = $this->searchDayByGuidOrDate($guid, $toDate);

        if (is_null($day)) {
            $day = new DayDiary();
            $day->guid = strtoupper(guid());
            $day->user_id = Auth::id();
            $day->to_date = $toDate;
        }

        $day->data = $data;

        $dr = new DayRation($day, new NutritionalValueCalculator());

        if ($dr->isValid()) {
            $day->data = json_encode($day->data);
            $day->save();
            $status = self::STATUS_SUCCESS;
        } else {
            $status = self::STATUS_ERROR;
            $error_message = $dr->getErrors();
        }

        return response()->json(
            [
                'guid' => $day->guid,
                'status' => $status,
                'error_message' => $error_message ?? null
            ]
        );
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        return view('FoodDiary.list', [
                'diaryList' => DayDiary::where('user_id', Auth::id())
                    ->orderBy('to_date', 'DESC')
                    ->take(30)
                    ->get()
        ]);
    }
}
