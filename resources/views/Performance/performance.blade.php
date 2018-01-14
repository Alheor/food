@extends('layout')
@section('content')
    <h1>Физические показатели</h1>
    <div class="navbar-collapse row bg-light header-menu">
        <div class="col-12 col-xl-12">
            @if(isset($success) && $success == 'new')
                <h2 class="text-success" style="font-size: 20px; text-align: center">Новая запись успешно создана!</h2>
            @endif
            @if(isset($success) && $success == 'edit')
                <h2 class="text-success" style="font-size: 20px; text-align: center">Запись успешно изменена!</h2>
            @endif
        </div>
        <div class="col-3 col-xl-3">
            <a href="{{ route('performance_cred', ['new']) }}" class="btn btn-success">Создать</a>
        </div>
        <div class="col-9 col-xl-9">
            <form class="form-inline  pull-right">
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
                <button class="btn btn-outline-info" type="submit">Найти</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <table class="table table-striped table-bordered table-sm diaryTable">
                <thead class="thead-inverse">
                <tr>
                    <th>Дата</th>
                    <th style="width: 37px;">Вес</th>
                    <th style="width: 64px;">Мышцы</th>
                    <th style="width: 37px;">Жир</th>
                    <th style="width: 39px;">Вода</th>
                    <th style="width: 25px;"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($performanceList as $performance)
                    <tr>
                        <td>
                            <div style="word-break: break-all;">@rusiandate($performance->to_date)</div>
                        </td>
                        <td>{{$performance->weight}}</td>
                        <td>{{$performance->general_musculature}}</td>
                        <td>{{$performance->general_fat}}</td>
                        <td>{{$performance->general_wather}}</td>
                        <td>
                            <div class="dropdown">
                                <i style="cursor: pointer; font-size: 18px;" class="material-icons" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">view_headline</i>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="{{ route('performance_cred', [$performance->guid]) }}" class="dropdown-item">
                                        <i class="material-icons">mode_edit</i> Изменить
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="pagination-div">
                {{ $performanceList->links() }}
            </div>
        </div>
    </div>
@endsection