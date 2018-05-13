@extends('layout')
@section('content')
    @php $pageTitle = 'Физические показатели'; @endphp
    <div class="navbar-collapse row bg-light header-menu">
        <div class="col-12 col-xl-12">
            @if(isset($success))
                <script type="application/javascript">
                    progress().endSuccess();
                </script>
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
                        <td style="padding-top: 0px;">
                            <div class="btn-group dropleft ">
                                <i class="fa fa-bars" style="font-size: 21px; cursor: pointer;" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                <div class="dropdown-menu other-menu">
                                    <ul>
                                        <li>
                                            <a href="{{ route('performance_cred', [$performance->guid]) }}">
                                                <i class="fa fa-pencil" aria-hidden="true"></i>
                                            </a>
                                        </li>
                                    </ul>
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