@extends('layout')
@section('content')
    <h1>Статистика пользователя {{$userName}}</h1>
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
    <div class="row">
        <div class="col-12 col-xl-12 main-widget-box">
            @if(!empty($data))
                <canvas id="myChart"></canvas>
                <script type="application/javascript">
                    var ctx = document.getElementById("myChart").getContext('2d');
                    var data = {!! json_encode($data) !!};

                    var json = {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($labels) !!},
                            datasets: []
                        },
                        options: {
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        beginAtZero:true,
                                        stepSize: {{$yAxesStepSize}}
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

                    var height = $('.container').height() - 500;
                    $('#myChart').attr('height', height < 300? 500 : height);


                    var myChart = new Chart(ctx, json);
                </script>
            @else
                Нет данных
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            Поделиться: {{url('statistic')}}?token={{$token}}
        </div>
    </div>
@endsection