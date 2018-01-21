<?php

namespace App\Http\Controllers;

use App\DayDiary;
use App\Performance;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function statistic(Request $request)
    {
        $type = $request->get('type') ?? 'day_diary';
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');
        $group = $request->get('group') ?? 'week';
        $token = $request->get('token');

        if(!is_null(Auth::id())) {
            $user = User::where('id', Auth::id())->first();
            $token = md5($user->email .
                Auth::id() .
                $user->name
            );
            $userName = $user->name;
            $user_id = Auth::id();

            if (empty($user->statistic_token)) {
                $user->statistic_token = $token;
                $user->save();
            }
        } else {
            if (is_null($token)) {
                return redirect('home');
            } else {
                $user = User::where('statistic_token', $token)->first();

                if (is_null($user)) {
                    return redirect('home');
                }

                $user_id = $user->id;
                $userName = $user->name;
            }
        }

        if (empty($from_date)) {
            $from_date = (new \DateTime())->modify('-30 days');
        } else {
            try {
                $from_date = (new \DateTime($from_date));
            } catch (\Throwable $t) {
                $from_date = (new \DateTime())->modify('-30 days');
            }
        }

        if (empty($to_date)) {
            $to_date = (new \DateTime());
        } else {
            try {
                $to_date = (new \DateTime($to_date));
            } catch (\Throwable $t) {
                $to_date = (new \DateTime());
            }
        }

        if ($type == 'physical_performance') {
            $statistic = $this->perfomancesStatistic($from_date, $to_date, $group, $user_id);
            $yAxesStepSize = 5;
        } else {
            $statistic = $this->dayDiaryStatistic($from_date, $to_date, $group, $user_id);
            $yAxesStepSize = 10;
        }

        return view('Statistic.statistic', [
            'type' => $type,
            'from_date' => $from_date->format('d.m.Y'),
            'to_date' => $to_date->format('d.m.Y'),
            'group' => $group,
            'labels' => $statistic[0],
            'data' => $statistic[1],
            'yAxesStepSize' => $yAxesStepSize,
            'userName' => $userName,
            'token' => $token
        ]);
    }

    /**
     * @param $from_date
     * @param $to_date
     * @param $group
     * @param $user_id
     * @return array
     */
    private function perfomancesStatistic($from_date, $to_date, $group, $user_id)
    {
        $perfomances = Performance::where('user_id', $user_id)
            ->whereBetween('to_date', [$from_date, $to_date])
            ->get();

        if ($perfomances->isEmpty()) {
            return [[],[]];
        }

        $newPerfomances = [];
        foreach ($perfomances as $perfomance) {
            if ($group == 'week') {
                $_group = (new \DateTime($perfomance->to_date))->format('W');
                $_date = (new \DateTime($perfomance->to_date))->modify('monday this week')->format('d-m-Y');

            } else if ($group == 'month') {
                $_group = (new \DateTime($perfomance->to_date))->format('m');
                $_date = '01-'.(new \DateTime($perfomance->to_date))->format('m-Y');
            } else if ($group == 'year') {
                $_group = (new \DateTime($perfomance->to_date))->format('Y');
                $_date = '01-01-'.(new \DateTime($perfomance->to_date))->format('Y');
            } else {
                $_group = (new \DateTime($perfomance->to_date))->format('d');
                $_date = (new \DateTime($perfomance->to_date))->format('d-m-Y');
            }

            $newPerfomances[$_group]['date'] = $_date;
            $newPerfomances[$_group]['info'][] = $perfomance;
        }

        foreach ($newPerfomances as &$perfomance) {
            $weight = 0;
            $general_musculature = 0;
            $general_fat = 0;
            $general_fat_percent = 0;
            $general_wather = 0;
            $metabolism = 0;

            foreach ($perfomance['info'] as $el) {
                $weight += $el->weight;
                $general_musculature += $el->general_musculature;
                $general_fat += $el->general_fat;
                $general_fat_percent += $el->general_fat_percent;
                $general_wather += $el->general_wather;
                $metabolism += $el->metabolism;
            }

            $perfomance['info'] = [
                'weight' => round($weight / count($perfomance['info'] ), 1),
                'general_musculature' => round($general_musculature / count($perfomance['info'] ), 1),
                'general_fat' => round($general_fat / count($perfomance['info'] ), 1),
                'general_fat_percent' => round($general_fat_percent / count($perfomance['info'] ), 1),
                'general_wather' => round($general_wather / count($perfomance['info'] ), 1),
                'metabolism' => round($metabolism / count($perfomance['info'] ), 1),
            ];
        }
        unset($perfomance);

        $labels = [];
        $data = [];

        $config = [
            'weight' => [
                'name' => 'Вес, кг.',
                'backgroundColor' => '#81d877',
                'borderColor' => '#81d877'
            ],
            'general_musculature' => [
                'name' => 'Мышцы, кг.',
                'backgroundColor' => '#e66161',
                'borderColor' => '#e66161'
            ],
//            'general_fat' => [
//                'name' => 'Жир, кг.',
//                'backgroundColor' => '',
//                'borderColor' => '#f2d638'
//            ],
            'general_fat_percent' => [
                'name' => 'Жир, %',
                'backgroundColor' => '#f2d638',
                'borderColor' => '#f2d638'
            ],
            'general_wather' => [
                'name' => 'Вода, л.',
                'backgroundColor' => '#7ed7d4',
                'borderColor' => '#7ed7d4'
            ],
            'metabolism' => [
                'name' => 'Метаболизм, Ккал. x 10',
                'backgroundColor' => '#777',
                'borderColor' => '#777',
                'params' => [
                    'hidden' => true
                ]
            ],

        ];

        foreach ($newPerfomances as $perfomance) {
            $labels[] = $perfomance['date'];
            $data['weight']['data'][] = $perfomance['info']['weight'];
            $data['general_musculature']['data'][] = $perfomance['info']['general_musculature'];
            //$data['general_fat']['data'][] = $perfomance['info']['general_fat'];
            $data['general_fat_percent']['data'][] = $perfomance['info']['general_fat_percent'];
            $data['general_wather']['data'][] = $perfomance['info']['general_wather'];
            $data['metabolism']['data'][] = round($perfomance['info']['metabolism']/10, 1);
        }

        $data['weight'] = array_merge($data['weight'], $config['weight']);
        $data['general_musculature'] = array_merge($data['general_musculature'], $config['general_musculature']);
        //$data['general_fat'] = array_merge($data['general_fat'], $config['general_fat']);
        $data['general_fat_percent'] = array_merge($data['general_fat_percent'], $config['general_fat_percent']);
        $data['general_wather'] = array_merge($data['general_wather'], $config['general_wather']);
        $data['metabolism'] = array_merge($data['metabolism'], $config['metabolism']);

        return [$labels, $data];
    }

    /**
     * @param $from_date
     * @param $to_date
     * @param $group
     * @param $user_id
     * @return array
     */
    private function dayDiaryStatistic($from_date, $to_date, $group, $user_id)
    {
        $dayDiary = DayDiary::where('user_id', $user_id)
            ->whereBetween('to_date', [$from_date, $to_date])
            ->get();

        if ($dayDiary->isEmpty()) {
            return [[],[]];
        }

        $newDayDiary = [];
        foreach ($dayDiary as $day) {
            if ($group == 'week') {
                $_group = (new \DateTime($day->to_date))->format('W');
                $_date = (new \DateTime($day->to_date))->modify('monday this week')->format('d-m-Y');

            } else if ($group == 'month') {
                $_group = (new \DateTime($day->to_date))->format('m');
                $_date = '01-'.(new \DateTime($day->to_date))->format('m-Y');
            } else if ($group == 'year') {
                $_group = (new \DateTime($day->to_date))->format('Y');
                $_date = '01-01-'.(new \DateTime($day->to_date))->format('Y');
            } else {
                $_group = (new \DateTime($day->to_date))->format('d');
                $_date = (new \DateTime($day->to_date))->format('d-m-Y');
            }

            $newDayDiary[$_group]['date'] = $_date;
            $newDayDiary[$_group]['info'][] = $day;
        }

        foreach ($newDayDiary as &$day) {
            $b = 0;
            $j = 0;
            $u = 0;
            $w = 0;
            $k = 0;

            foreach ($day['info'] as $el) {
                $b += $el->b;
                $j += $el->j;
                $u += $el->u;
                $w += $el->w;
                $k += $el->k;
            }

            $day['info'] = [
                'b' => round($b / count($day['info'] ), 1),
                'j' => round($j / count($day['info'] ), 1),
                'u' => round($u / count($day['info'] ), 1),
                'w' => round($w / count($day['info'] ), 1),
                'k' => round($k / count($day['info'] ), 1)
            ];
        }
        unset($day);

        $labels = [];
        $data = [];

        $config = [
            'b' => [
                'name' => 'Белки, г.',
                'backgroundColor' => '#81d877',
                'borderColor' => '#81d877'
            ],
            'j' => [
                'name' => 'Жиры, г.',
                'backgroundColor' => '#f2d638',
                'borderColor' => '#f2d638'
            ],
            'u' => [
                'name' => 'Углеводы,  г.',
                'backgroundColor' => '#e66161',
                'borderColor' => '#e66161'
            ],
            'w' => [
                'name' => 'Вес еды,  г. x 10',
                'backgroundColor' => '#7ed7d4',
                'borderColor' => '#7ed7d4',
                'params' => [
                    'hidden' => true
                ]
            ],
            'k' => [
                'name' => 'Ккал,  г. x 10',
                'backgroundColor' => '#777',
                'borderColor' => '#777'
            ]
        ];

        foreach ($newDayDiary as $day) {
            $labels[] = $day['date'];
            $data['b']['data'][] = $day['info']['b'];
            $data['j']['data'][] = $day['info']['j'];
            $data['u']['data'][] = $day['info']['u'];
            $data['w']['data'][] = round($day['info']['w']/10, 1);
            $data['k']['data'][] = round($day['info']['k']/10, 1);
        }

        $data['b'] = array_merge($data['b'], $config['b']);
        $data['j'] = array_merge($data['j'], $config['j']);
        $data['u'] = array_merge($data['u'], $config['u']);
        $data['w'] = array_merge($data['w'], $config['w']);
        $data['k'] = array_merge($data['k'], $config['k']);

        return [$labels, $data];
    }
}
