@extends('layout')
@section('content')
    <?php $pageTitle = 'Продукты и блюда'; ?>
    <div class="main-widget">
        <div class="main-widget-header">
            Продукты
        </div>
        <div class="row">
            <div class="col-12 col-xl-12">
                @if(isset($product_success) && $product_success == 'new' || $product_success == 'edit')
                    <h6 class="text-success" style="font-size: 20px; text-align: center;">Операция успешно выполнена.</h6>
                @endif
            </div>
        </div>
        <div class="main-widget-widget">
            <div class="row">
                <div class="col-12">
                    <form class="form-inline pull-right" method="get">
                        <input
                                autocomplete="off"
                                class="form-control"
                                value="{{$product_search}}"
                                name="product_search"
                                style="width: 150px; margin-right: 5px;"
                                type="text"
                                placeholder="Найти продукт"
                        >
                        <button class="btn btn-outline-info" type="submit">Найти</button>
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
                                    <a href="{{ route('new_product', ['oper' => 'new']) }}">
                                        <i class="fa fa-plus product-add" aria-hidden="true" title="Новый продукт"></i>
                                    </a>
                                </div>
                                Наименование
                            </th>
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
                                    <div style="word-break: break-all;">

                                        {{$product->name}}<br>
                                        <span class="small">
                                        <sup>TM: <b>{{$product->manufacturer->name}}</b> |
                                        Автор: <b>{{$product->user->name}}</b></sup>
                                    </span>
                                    </div>
                                </td>
                                <td style="background-color: #c3e6cb">{{$product->b}}</td>
                                <td style="background-color: #ffeeba">{{$product->j}}</td>
                                <td style="background-color: #f5c6cb">{{$product->u}}</td>
                                <td>{{(int)$product->k}}</td>
                                <td style="padding-top: 0px;">
                                    <div class="btn-group dropleft ">
                                        <i class="fa fa-bars" style="font-size: 18px;" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                        <div class="dropdown-menu other-menu">
                                            <ul>
                                                <li>
                                                    <a href="{{route('new_product', ['guid' => $product->guid, 'copy'=> true], true)}}">
                                                        <i class="fa fa-files-o" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('new_product', ['oper' => $product->guid]) }}">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if(empty($products))
                            <tr>
                                <td colspan="6">
                                    @if(!empty($product_search))
                                        Ничего не найдено
                                    @else
                                        Введите слово (мин. 3 символа) для поиска ...
                                    @endif
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="main-widget">
        <div class="main-widget-header">
            Блюда
        </div>
        <div class="row">
            <div class="col-12 col-xl-12">
                @if(isset($dish_success) && $dish_success == 'new' || $dish_success == 'edit')
                    <h6 class="text-success" style="font-size: 20px; text-align: center;">Операция успешно выполнена.</h6>
                @endif
            </div>
        </div>
        <div class="main-widget-widget">
            <div class="row">
                <div class="col-12">
                    <form class="form-inline pull-right" method="get">
                        <input
                                autocomplete="off"
                                class="form-control"
                                value="{{$dish_search}}"
                                name="dish_search"
                                style="width: 150px; margin-right: 5px;"
                                type="text"
                                placeholder="Найти блюдо"
                        >
                        <button class="btn btn-outline-info" type="submit">Найти</button>
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
                                    <a href="{{ route('new_dish', ['oper' => 'new']) }}">
                                        <i class="fa fa-plus product-add" aria-hidden="true" title="Новый продукт"></i>
                                    </a>
                                </div>
                                Наименование
                            </th>
                            <th style="width: 37px;">Б</th>
                            <th style="width: 37px;">Ж</th>
                            <th style="width: 37px;">У</th>
                            <th style="width: 37px;">Ккал</th>
                            <th style="width: 25px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($dishes as $dish)
                            <tr>
                                <td>
                                    <div style="word-break: break-all;">

                                        {{$dish->name}}<br>
                                        <span class="small">
                                        <sup>Автор: <b>{{$dish->user->name}}</b></sup>
                                    </span>
                                    </div>
                                </td>
                                <td style="background-color: #c3e6cb">{{$dish->b}}</td>
                                <td style="background-color: #ffeeba">{{$dish->j}}</td>
                                <td style="background-color: #f5c6cb">{{$dish->u}}</td>
                                <td>{{(int)$dish->k}}</td>
                                <td style="padding-top: 0px;">
                                    <div class="btn-group dropleft ">
                                        <i class="fa fa-bars" style="font-size: 18px;" aria-hidden="true" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
                                        <div class="dropdown-menu other-menu">
                                            <ul>
                                                <li>
                                                    <a href="{{route('new_dish', ['guid' => $dish->guid, 'copy'=> true])}}">
                                                        <i class="fa fa-files-o" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="{{ route('new_dish', ['oper' => $dish->guid]) }}">
                                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        @if(empty($dishes))
                            <tr>
                                <td colspan="6">
                                    @if(!empty($dish_search))
                                        Ничего не найдено
                                    @else
                                        Введите слово (мин. 3 символа) для поиска ...
                                    @endif
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection