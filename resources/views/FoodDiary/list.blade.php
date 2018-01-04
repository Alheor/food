@extends('layout')
@section('content')
    <a class="btn btn-primary" href="{{ route('foodDiaryNewDay') }}" role="button">Новый день</a>
    <table class="table table-striped table-bordered table-sm">
        <thead class="thead-inverse">
        <tr>
            <th>Дата</th>
            <th style="width: 90px;">Мой вес</th>
            <th style="width: 90px;">Вес пищи</th>
            <th style="width: 50px;">Б</th>
            <th style="width: 50px;">Ж</th>
            <th style="width: 50px;">У</th>
            <th style="width: 50px;">Ккал</th>
            <th style="width: 33px;"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($diaryList as $diary)
            <tr>
                <td>@rusiandate($diary->to_date)</td>
                <td style="background-color: #fff">{{$diary->my_weight}} кг.</td>
                <td>{{$diary->w}} гр.</td>
                <td style="background-color: #c3e6cb">{{$diary->b}}</td>
                <td style="background-color: #ffeeba">{{$diary->j}}</td>
                <td style="background-color: #f5c6cb">{{$diary->u}}</td>
                <td>{{$diary->k}}</td>
                <td>
                    <a class="material-icons" href="{{route('foodDiaryLoadDay', ['guid' => $diary->guid], true)}}">mode_edit</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-end">
            <li class="page-item disabled">
                <a class="page-link" href="#" tabindex="-1">Предыдущая</a>
            </li>
            <li class="page-item"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item">
                <a class="page-link" href="#">Следующая</a>
            </li>
        </ul>
    </nav>
@endsection