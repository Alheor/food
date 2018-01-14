@extends('layout')
@section('content')
    <h1>Статистика</h1>
    <div class="navbar-collapse row bg-light header-menu">
        <form method="GET">
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
                    <input type="text" class="form-control" value="{{ $from_date }}" name="to_date">
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
                <button type="submit" style="margin-top: 32px;" class="btn btn-primary">Применить</button>
            </div>
        </form>
    </div>
    <div class="navbar-collapse row bg-light header-menu">
        <div class="col-12 col-xl-12">
            Нет данных
        </div>
    </div>
@endsection