<?php

namespace App\Http\Controllers;

use App\Performance;
use Faker\Provider\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PerformanceController extends Controller
{
    public function list(Request $request)
    {
        $to_date = $request->get('to_date');

        if(!empty($to_date)) {
            try {
                $date =  new \DateTime($to_date);
            } catch (\Throwable $t) {
                $date = new \DateTime();
            }

            $products = Performance::orderBy('to_date', 'DESC')
                ->where('user_id', Auth::id())
                ->where('to_date', $date)
                ->simplePaginate(30);
        } else {
            $products = Performance::orderBy('to_date', 'DESC')
                ->where('user_id', Auth::id())
                ->simplePaginate(30);
        }

        return view('Performance.performance', [
            'performanceList' => $products,
            'success' => $request->get('success'),
            'to_date' => $to_date
        ]);
    }

    public function crEd(Request $request, $oper) {
        if ($request->method() == 'GET') {
            if($oper != 'new') {
                $performance = Performance::where('guid', $oper)->first();
            }

            return view('Performance.crEd', [
                'form' => 'new_form',
                'performance' => $performance ?? null
            ]);
        } elseif ($request->method() == 'POST') {

            $validator = Validator::make($request->all(), [
                'weight'                => 'required|numeric',
                'general_musculature'   => 'numeric|nullable',
                'general_fat'           => 'numeric|nullable',
                'general_fat_percent'   => 'numeric|max:100|nullable',
                'general_wather'        => 'numeric|nullable',
                'metabolism'            => 'numeric|nullable'
            ]);

            $validator->validate();

            try {
                $toDate = (new \DateTime($request->get('to_date')))->modify('midnight');
            } catch (\Throwable $t) {
                abort(500, "Date {$request->get('to_date')} not recognized");
            }

            if($oper == 'new') {
                $performance = new Performance();
                $performance->user_id = Auth::id();
                $performance->guid = strtoupper(guid());
                $performance->to_date = $toDate;
            } else {
                $performance = Performance::where('guid', $oper)->first();

                if (is_null($performance)) {
                    abort(404);
                }
            }

            DB::beginTransaction();

            try {
                $performance->weight = (float)$request->get('weight');
                $performance->general_musculature = (float)$request->get('general_musculature');
                $performance->general_fat = (float)$request->get('general_fat');
                $performance->general_fat_percent = (float)$request->get('general_fat_percent');
                $performance->general_wather = (float)$request->get('general_wather');
                $performance->metabolism = (float)$request->get('metabolism');

                $performance->save();
            } catch (\Throwable $e) {
                DB::rollBack();

                return new \Exception(
                    'Не удалось создать объект: ' . $e->getMessage()
                );
            }

            DB::commit();

            return redirect()->route('performance_list', ['success' => $oper == 'new'? 'new': 'edit']);
        } else {
            abort(403);
        }
    }
}