@extends('layout')
@section('content')
    @php $pageTitle = 'Дневник питания'; @endphp
    <div class="col-12 main-widget-box">
        <div class="row">
            <div class="col-3 col-xl-3">
                <a class="btn btn-success" href="{{ route('foodDiaryLoadDay') }}">Сегодня</a>
            </div>
            <div class="col-9 col-xl-9">
                <form class="form-inline pull-right prod-search-form" method="get">
                    <input class="form-control" value="{{$search}}" name="search" style="width: 150px; margin-right: 5px;" type="text" placeholder="Найти" aria-label="Найти">
                    <button class="btn btn-outline-info" type="submit" title="Поиск" >
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-outline-danger"
                            name="clear"
                            title="Сброс"
                            type="submit"
                            style="margin-left: 5px;">
                        <i class="fa fa-eraser" aria-hidden="true"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-bordered table-sm diaryTable">
                <thead class="thead-inverse">
                <tr class="tabel-td">
                    <th><div class="pull-left add-new-day">
                            <a href="{{ route('foodDiaryNewDay') }}">
                                <i class="fa fa-plus product-add" aria-hidden="true" title="Новый день"></i>
                            </a>
                        </div>Дата</th>
                    <th style="width: 37px;">Вес</th>
                    <th style="width: 37px;">Б</th>
                    <th style="width: 37px;">Ж</th>
                    <th style="width: 37px;">У</th>
                    <th style="width: 37px;">Ккал</th>
                    <th style="width: 25px;"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($diaryList as $diary)
                    <tr class="tabel-td">
                        <td>@rusiandate($diary->to_date)</td>
                        <td>{{$diary->w}}</td>
                        <td style="background-color: #c3e6cb;">{{floor($diary->b)}}</td>
                        <td style="background-color: #ffeeba;">{{floor($diary->j)}}</td>
                        <td style="background-color: #f5c6cb;">{{floor($diary->u)}}</td>
                        <td>{{(int)$diary->k}}</td>
                        <td style="padding-top: 0px;">
                            <div class="btn-group dropleft">
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
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <div class="pagination-div">
                {{ $diaryList->links() }}
            </div>
        </div>
    </div>
@endsection