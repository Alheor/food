@extends('layout')
@section('content')
    @php $pageTitle = 'Продукты'; @endphp
    <div class="row">
        @if(isset($success))
            <script type="application/javascript">
                progress().endSuccess();
            </script>
        @endif

        <div class="col-12 main-widget-box">
            <div class="row">
                <div class="col-3 col-xl-3">
                    <a href="{{ route('new_product', ['oper' => 'new']) }}" class="btn btn-success">Новый</a>
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