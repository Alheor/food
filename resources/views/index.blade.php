@extends('layout')
@section('content')
    <?php $pageTitle = 'Сводка'; ?>
    <div class="col-12 main-widget">
        <div class="main-widget-header">
            Дневник питания
        </div>
        <div class="main-widget-widget" style="padding-top: 0;">
            <div class="col-12">
                <div>
                    <table class="table table-bordered table-sm diaryTableResult">
                        <thead>
                        <tr>
                            <th colspan="5">
                                <div class="font-weight-bold">ВСЕГО ЗА СЕГОДНЯ</div>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td style="width: 50px; text-align: center;">Вес, гр.</td>
                            <td style="width: 44px; text-align: center;">Белки, гр.</td>
                            <td style="width: 44px; text-align: center;">Жиры, гр.</td>
                            <td style="width: 44px; text-align: center;">Угл., гр.</td>
                            <td style="width: 50px; text-align: center;">Ккал.</td>
                        </tr>
                        <tr>
                            <td style="width: 50px; text-align: center;">@if(isset($day)) {{$day->w}} @else 0 @endif</td>
                            <td style="width: 44px; text-align: center; background-color: #c3e6cb;" class="resultB">@if(!empty($day)) {{$day->b}} @else 0 @endif</td>
                            <td style="width: 44px; text-align: center; background-color: #ffeeba; " class="resultJ">@if(!empty($day)) {{$day->j}} @else 0 @endif</td>
                            <td style="width: 44px; text-align: center; background-color: #f5c6cb;" class="resultU">@if(!empty($day)) {{$day->u}} @else 0 @endif</td>
                            <td style="width: 50px; text-align: center;" class="resultK">@if(!empty($day)) {{$day->k}} @else 0 @endif</td>
                        </tr>
                        @php
                            if(!empty($day)) {
                                $summBju = $day->b + $day->j + $day->u;
                                $percentB = round($day->b * 100 / $summBju, 1);
                                $percentJ = round($day->j * 100 / $summBju, 1);
                                $percentU = round($day->u * 100 / $summBju, 1);
                            }
                        @endphp
                        <tr>
                            <td style="width: 50px; text-align: center;"></td>
                            <td style="width: 44px; text-align: center; background-color: #c3e6cb;" class="resultB">{{$percentB ?? 0}}%</td>
                            <td style="width: 44px; text-align: center; background-color: #ffeeba;" class="resultJ">{{$percentJ ?? 0}}%</td>
                            <td style="width: 44px; text-align: center; background-color: #f5c6cb;" class="resultU">{{$percentU ?? 0}}%</td>
                            <td style="width: 50px; text-align: center;"></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="main-widget-widget">
            <div class="row">
                <div class="col-3">
                    <a class="btn btn-success" href="{{ route('foodDiaryLoadDay') }}">Сегодня</a>
                </div>
                <div class="col-9">
                    <form class="form-inline pull-right">
                        <div class="input-group date" style="margin-right: 5px; width: 150px;">
                            <input type="text" placeholder="Поиск дня" class="form-control" value="{{ $to_date }}" name="diary_to_date">
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
                        <button class="btn btn-outline-danger" name="diary_clear" title="Сброс" type="submit" style="margin-right: 5px;" >
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-outline-info" type="submit" title="Поиск" >
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="main-widget-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-bordered table-sm diaryTable">
                        <thead class="thead-inverse">
                        <tr class="tabel-td">
                            <th>
                                <div class="pull-left add-new-day">
                                    <a href="{{ route('foodDiaryNewDay') }}">
                                        <i class="fa fa-plus product-add" aria-hidden="true" title="Новый день"></i>
                                    </a>
                                </div>
                                Дата
                            </th>
                            <th style="width: 37px;">Вес</th>
                            <th style="width: 30px;">Б</th>
                            <th style="width: 30px;">Ж</th>
                            <th style="width: 30px;">У</th>
                            <th style="width: 37px;">Ккал</th>
                            <th style="width: 25px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($diaryList as $diary)
                            <tr class="tabel-td">
                                <td>
                                    @if((new DateTime($diary->to_date))->getTimestamp() === (new DateTime('today'))->getTimestamp())
                                        Сегодня
                                    @else
                                        @rusiandate($diary->to_date)
                                    @endif
                                </td>
                                <td>{{$diary->w}}</td>
                                <td style="background-color: #c3e6cb;">{{floor($diary->b)}}</td>
                                <td style="background-color: #ffeeba;">{{floor($diary->j)}}</td>
                                <td style="background-color: #f5c6cb;">{{floor($diary->u)}}</td>
                                <td>{{(int)$diary->k}}</td>
                                <td style="padding-top: 0px;">
                                    <div class="btn-group dropleft ">
                                        <i class="fa fa-bars" style="font-size: 21px; cursor: pointer;" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                        <div class="dropdown-menu other-menu">
                                            <ul>
                                                <li>
                                                    <a href="{{route('foodDiaryСopyDay', ['guid' => $diary->guid], true)}}">
                                                        <i class="fa fa-files-o" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{route('foodDiaryLoadDay', ['guid' => $diary->guid], true)}}">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    Ничего не найдно...
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <a href="{{ route('foodDiaryList') }}" class="pull-right">все дни питания</a>
                </div>
            </div>
        </div>
    </div>
    <div class="main-widget">
        <div class="main-widget-header">
            Физические показатели
        </div>
        <div class="main-widget-widget">
            <div class="row">
                <div class="col-12">
                    <form class="form-inline pull-right">
                        <div class="input-group date" style="margin-right: 5px; width: 150px;">
                            <input type="text" placeholder="Поиск дня"  class="form-control" value="{{ $to_date }}" name="perfomance_to_date">
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
                        <button class="btn btn-outline-danger" name="perfomance_clear" title="Сброс" type="submit" style="margin-right: 5px;" >
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </button>
                        <button class="btn btn-outline-info" type="submit" title="Поиск" >
                            <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="main-widget-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-bordered table-sm diaryTable">
                        <thead class="thead-inverse">
                        <tr>
                            <th>
                                <div class="pull-left add-new-day">
                                    <a href="{{ route('performance_cred', ['new']) }}" >
                                        <i class="fa fa-plus product-add" aria-hidden="true" title="Новая запись"></i>
                                    </a>
                                </div>
                                Дата
                            </th>
                            <th style="width: 37px;">Вес</th>
                            <th style="width: 64px;">Мышцы</th>
                            <th style="width: 37px;">Жир</th>
                            <th style="width: 39px;">Вода</th>
                            <th style="width: 25px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($performanceList as $performance)
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
                        @empty
                            <tr>
                                <td colspan="6">
                                    Ничего не найдно...
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    <a href="{{ route('performance_list') }}" class="pull-right">все физические показатели</a>
                </div>
            </div>
        </div>
    </div>
@endsection