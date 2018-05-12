@extends('layout')
@section('content')
    @php $pageTitle = $userName . '.статистика'; @endphp
    <div class="row main-widget-box">
        <form method="GET">
            <input type="hidden" id="token" name="token" value="{{$token}}"/>
            <div class="form-group pull-left" style="margin-right: 5px; margin-bottom: 2px;">
                <label for="type">Тип:</label>
                <select class="form-control" id="type" name="type">
                    <option value="physical_performance" @if($type == 'hysical_performance') selected="" @endif>Физ. показатели</option>
                    <option value="day_diary" @if($type == 'day_diary') selected="" @endif>Дневник питания</option>
                </select>
            </div>
            <div class="form-group pull-left" style="margin-bottom: 2px;">
                <label for="type">Период с:</label>
                <div class="input-group date" style="width: 150px; margin-right: 5px;">
                    <input type="text" class="form-control" value="{{ $from_date }}" name="from_date">
                    <span class="input-group-addon">
                    <i class="fa fa-calendar" aria-hidden="true" style="cursor: pointer;"></i>
                </span>
                </div>
            </div>
            <div class="form-group pull-left" style="margin-bottom: 2px;">
                <label for="type">Период по:</label>
                <div class="input-group date" style="width: 150px; margin-right: 5px;">
                    <input type="text" class="form-control" value="{{ $to_date }}" name="to_date">
                    <span class="input-group-addon">
                    <i class="fa fa-calendar" aria-hidden="true" style="cursor: pointer;"></i>
                </span>
                </div>
                <script type="application/javascript">
                    $('.input-group.date').datepicker({
                        language: "ru",
                        todayHighlight: true,
                        daysOfWeekHighlighted: "0,6"
                    });
                </script>
            </div>
            <div class="form-group pull-left" style="margin-bottom: 2px;">
                <label for="type">Сгруппировать по:</label>
                <div class="input-group date" style="width: 150px; margin-right: 5px;">
                    <select class="form-control" id="group" name="group">
                        <option value="day" @if($group == 'day') selected="" @endif>Дням</option>
                        <option value="week" @if($group == 'week') selected="" @endif>Неделям</option>
                        <option value="month" @if($group == 'month') selected="" @endif>Месяцам</option>
                        <option value="year" @if($group == 'year') selected="" @endif>Годам</option>
                    </select>
                    </div>
            </div>
            <div class="form-group pull-left" style="margin-bottom: 2px;">
                <button type="submit" style="margin-top: 32px;" class="btn btn-primary">Применить</button>
            </div>
        </form>
    </div>
    <script type="application/javascript">
        var height = $('.container').height() - 900;

        function renderChart(canvas_id, data, labels, yAxesStepSize) {
            var ctx = document.getElementById(canvas_id).getContext('2d');

            var json = {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: []
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                //stepSize: yAxesStepSize
                            }
                        }],
                        xAxes: [{
                            stacked: false,
                            ticks: {
                                autoSkip: false
                            }
                        }]
                    }
                }
            };

            var res = [];
            for (var i in data) {
                var el = {fill: false};
                el['label'] =  data[i]['name'];
                el['backgroundColor'] = data[i]['backgroundColor'];
                el['borderColor'] = data[i]['borderColor'];
                el['data'] = data[i]['data'];
                el['radius'] = 5;
                el['pointStyle'] = 'rect';

                if (typeof data[i]['params'] !== "undefined") {
                    for (var z in data[i]['params']) {
                        el[z] = data[i]['params'][z];
                    }
                }

                res.push(el);
            }

            json.data.datasets = res;

            ctx.canvas.width  = window.innerWidth;
            ctx.canvas.height = window.innerHeight;

            $('#' + canvas_id).attr('height', height < 300? 300 : height);

            new Chart(ctx, json);
        }
    </script>
    @php
        function modifyStatisticData($data, $labels) {
            $newLabels = [];
            $newData = [];
            $first = true;

            foreach ($data as $key1 => $el1) {
                $newData[$key1] = $el1;
                unset($newData[$key1]['data']);
                foreach ($el1['data'] as $key => $el) {
                    if ($el > 0) {
                        if ($first) {
                            $newLabels[] = $labels[$key];
                        }
                        $newData[$key1]['data'][] = $el;
                    }
                }
                $first = false;
            }

            return [$newData, $newLabels];
        }
    @endphp

    @if(!empty($statistic['weight']))
        <div class="row">
            <div class="col-12 col-xl-12 main-widget-box">
                <canvas id="myChartWeight"></canvas>
                <script type="application/javascript">
                    @php
                        $data = modifyStatisticData($statistic['weight'], $labels);
                        $statistic['weight'] = $data[0];
                    @endphp
                    renderChart('myChartWeight', {!! json_encode($statistic['weight']) !!}, {!! json_encode($data[1]) !!});
                </script>
        </div>
    </div>
    @endif
    @if(!empty($statistic['metabolism']))
        <div class="row">
            <div class="col-12 col-xl-12 main-widget-box">
                <canvas id="myChartMetabolism"></canvas>
                <script type="application/javascript">
                    @php
                        $data = modifyStatisticData($statistic['metabolism'], $labels);
                        $statistic['metabolism'] = $data[0];
                    @endphp
                    renderChart('myChartMetabolism', {!! json_encode($statistic['metabolism']) !!}, {!! json_encode($data[1]) !!});
                </script>
            </div>
        </div>
    @endif
    @if(!empty($statistic['general_musculature']))
        <div class="row">
            <div class="col-12 col-xl-12 main-widget-box">
                <canvas id="myChartMusculature"></canvas>
                <script type="application/javascript">
                    @php
                        $data = modifyStatisticData($statistic['general_musculature'], $labels);
                        $statistic['general_musculature'] = $data[0];
                    @endphp
                    renderChart('myChartMusculature', {!! json_encode($statistic['general_musculature']) !!}, {!! json_encode($data[1]) !!});
                </script>
            </div>
        </div>
    @endif
    @if(!empty($statistic['general_fat']))
        <div class="row">
            <div class="col-12 col-xl-12 main-widget-box">
                <canvas id="myChartFat"></canvas>
                <script type="application/javascript">
                    @php
                        $data = modifyStatisticData($statistic['general_fat'], $labels);
                        $statistic['general_fat'] = $data[0];
                    @endphp
                    renderChart('myChartFat', {!! json_encode($statistic['general_fat']) !!}, {!! json_encode($data[1]) !!});
                </script>
            </div>
        </div>
    @endif
    @if(!empty($statistic['general_wather']))
        <div class="row">
            <div class="col-12 col-xl-12 main-widget-box">
                <canvas id="myChartWater"></canvas>
                <script type="application/javascript">
                    @php
                        $data = modifyStatisticData($statistic['general_wather'], $labels);
                        $statistic['general_wather'] = $data[0];
                    @endphp
                    renderChart('myChartWater', {!! json_encode($statistic['general_wather']) !!}, {!! json_encode($data[1]) !!});
                </script>
            </div>
        </div>
    @endif
    @if(!empty($statistic['bju']))
        <div class="row">
            <div class="col-12 col-xl-12 main-widget-box">
                <canvas id="myChartBju"></canvas>
                <script type="application/javascript">
                    @php
                        $data = modifyStatisticData($statistic['bju'], $labels);
                        $statistic['bju'] = $data[0];
                    @endphp
                    renderChart('myChartBju', {!! json_encode($statistic['bju']) !!}, {!! json_encode($data[1]) !!});
                </script>
            </div>
        </div>
    @endif
    @if(!empty($statistic['wk']))
        <div class="row">
            <div class="col-12 col-xl-12 main-widget-box">
                <canvas id="myChartWk"></canvas>
                <script type="application/javascript">@php
                        $data = modifyStatisticData($statistic['wk'], $labels);
                        $statistic['wk'] = $data[0];
                    @endphp

                    renderChart('myChartWk', {!! json_encode($statistic['wk']) !!}, {!! json_encode($data[1]) !!});
                </script>
            </div>
        </div>
    @endif
    <div class="row" style="margin-bottom: 10px !important;">
        <div class="col-12">
            Поделиться: {{url('statistic')}}?token={{$token}}
        </div>
    </div>
@endsection