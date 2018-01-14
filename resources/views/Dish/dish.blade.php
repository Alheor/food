@extends('layout')
@section('content')
    <h1>Блюда</h1>
    <div class="navbar-collapse row bg-light header-menu">
        <div class="col-3 col-xl-3">
            <a href="{{ route('new_dish', ['new']) }}" class="btn btn-success">Новое</a>
        </div>
        <div class="col-9 col-xl-9">
            <form class="form-inline pull-right">
                <input class="form-control" value="{{$search}}" name="search" style="width: 150px; margin-right: 5px;" type="text" placeholder="Найти" aria-label="Найти">
                <button class="btn btn-outline-info" type="submit">Найти</button>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-xl-12">
            <table class="table table-striped table-bordered table-sm diaryTable">
                <thead class="thead-inverse">
                <tr>
                    <th>Наименование</th>
                    <th style="width: 37px;">Б</th>
                    <th style="width: 37px;">Ж</th>
                    <th style="width: 37px;">У</th>
                    <th style="width: 37px;">Ккал</th>
                    <th style="width: 25px;"></th>
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
                        <td>{{(int)$dish->k}}</td>
                        <td>
                            <div class="dropdown">
                                <i style="cursor: pointer; font-size: 18px;" class="material-icons" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">view_headline</i>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="{{ route('new_dish', [$dish->guid]) }}" class="dropdown-item">
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
                {{ $dishList->links() }}
            </div>
        </div>
    </div>
@endsection