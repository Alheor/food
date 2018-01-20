@extends('layout')
@section('content')
    <div class="row">
        <div class="col-12">
    <h1>Дневник питания</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12 main-widget-box">
            <a class="btn btn-success" href="{{ route('foodDiaryLoadDay') }}" role="button">Сегодняшний рацион</a>
            <a class="btn btn-secondary" href="{{ route('foodDiaryNewDay') }}" role="button">Новый день</a>
        </div>
        <div class="col-12">
            <table class="table table-striped table-bordered table-sm diaryTable">
                <thead class="thead-inverse">
                <tr class="tabel-td">
                    <th>Дата</th>
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
                        <td>
                            <a class="material-icons" style="font-size: 18px; color: black" href="{{route('foodDiaryLoadDay', ['guid' => $diary->guid], true)}}">mode_edit</a>
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