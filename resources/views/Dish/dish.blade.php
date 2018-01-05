@extends('layout')
@section('content')
    <h1>Блюда</h1>
    <div class="pos-f-t">
        <div class="collapse" id="navbarToggleExternalContent">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="navbar-collapse">
                    <ul class="navbar-nav mr-auto">
                        <li>
                            <a href="{{ route('new_dish', ['new']) }}" class="btn btn-success">Новое</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li>
                            <form class="form-inline my-2 my-lg-0">
                                <input class="form-control mr-sm-2" type="text" placeholder="Найти" aria-label="Найти">
                                <button class="btn btn-outline-info my-2 my-sm-0" type="submit">Найти</button>
                            </form>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <nav class="navbar navbar-light bg-light">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </nav>
    </div>
    <table class="table table-striped table-bordered table-sm">
        <thead class="thead-inverse">
        <tr>
            <th>Наименование</th>
            <th style="width: 37px;">Б</th>
            <th style="width: 37px;">Ж</th>
            <th style="width: 37px;">У</th>
            <th style="width: 37px;">Ккал</th>
            <th style="width: 35px;"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($dishList as $dish)
            <tr>
                <td>
                    <div>{{$dish->name}}</div>
                </td>
                <td style="background-color: #c3e6cb">{{$dish->b}}</td>
                <td style="background-color: #ffeeba">{{$dish->j}}</td>
                <td style="background-color: #f5c6cb">{{$dish->u}}</td>
                <td>{{$dish->k}}</td>
                <td>
                    <div class="dropdown">
                        <i style="cursor: pointer;" class="material-icons" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">view_headline</i>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a href="{{ route('new_dish', [$dish->id]) }}" class="dropdown-item">
                                <i class="material-icons">mode_edit</i> Изменить
                            </a>
                        </div>
                    </div>
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