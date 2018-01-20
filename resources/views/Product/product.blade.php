@extends('layout')
@section('content')
    <div class="row">
        <div class="col-12">
            <h1>Продукты</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-12 main-widget-box">
            <div class="navbar-collapse row">
                <div class="col-12 col-xl-12">
                    @if(isset($success) && $success == 'new')
                        <h2 class="text-success" style="font-size: 20px; text-align: center;">Новая запись успешно создана!</h2>
                    @endif
                    @if(isset($success) && $success == 'edit')
                        <h2 class="text-success" style="font-size: 20px; text-align: center">Запись успешно изменена!</h2>
                    @endif
                </div>
                <div class="col-3 col-xl-3">
                    <a href="{{ route('new_product') }}" class="btn btn-success">Новый</a>
                </div>
                <div class="col-9 col-xl-9">
                    <form class="form-inline pull-right" method="get">
                        <input class="form-control" value="{{$search}}" name="search" style="width: 150px; margin-right: 5px;" type="text" placeholder="Найти" aria-label="Найти">
                        <button class="btn btn-outline-info" type="submit">Найти</button>
                    </form>
                </div>
            </div>
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
                @foreach($products as $product)
                    <tr>
                        <td>
                            <div style="word-break: break-all;">{{$product->name}}
                                <span class="small"><sup>{{$product->manufacturer->name}}</sup></span>
                            </div>
                        </td>
                        <td style="background-color: #c3e6cb">{{$product->b}}</td>
                        <td style="background-color: #ffeeba">{{$product->j}}</td>
                        <td style="background-color: #f5c6cb">{{$product->u}}</td>
                        <td>{{(int)$product->k}}</td>
                        <td>
                            <div class="dropdown">
                                <i style="cursor: pointer; font-size: 18px;" class="material-icons" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">view_headline</i>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a href="#" class="dropdown-item">
                                        <i class="material-icons">mode_edit</i> Изменить
                                    </a>
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
                {{ $products->links() }}
            </div>
        </div>
    </div>

@endsection