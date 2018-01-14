<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function statistic(Request $request)
    {
        $type = $request->get('type') ?? 'physical_performance';
        $from_date = $request->get('from_date');
        $to_date = $request->get('to_date');

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

        return view('Statistic.statistic', [
            'type' => $type,
            'from_date' => $from_date->format('d.m.Y'),
            'to_date' => $to_date->format('d.m.Y')
        ]);
    }
}
