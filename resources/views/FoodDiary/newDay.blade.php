@extends('layout')
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-5 col-lg-7 col-xl-8">
            <h4>Дневник питания на сегодня</h4>
        </div>
        <div class="col-sm-12 col-md-7 col-lg-5 col-xl-4">
            <div class="diaryTableResultDiv">
                <table class="table table-bordered table-sm diaryTableResult">
                    <thead>
                    <tr>
                        <th colspan="5">
                            <div class="font-weight-bold">ВСЕГО ЗА ДЕНЬ</div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td style="width: 50px; text-align: center;">Вес</td>
                        <td style="width: 44px; text-align: center;">Б</td>
                        <td style="width: 44px; text-align: center;">Ж</td>
                        <td style="width: 44px; text-align: center;">У</td>
                        <td style="width: 50px; text-align: center;">Ккал</td>
                    </tr>
                    <tr>
                        <td style="width: 50px; text-align: center;">0</td>
                        <td style="width: 44px; text-align: center; background-color: #c3e6cb;">0</td>
                        <td style="width: 44px; text-align: center; background-color: #ffeeba; ">0</td>
                        <td style="width: 44px; text-align: center; background-color: #f5c6cb;">0</td>
                        <td style="width: 50px; text-align: center;">0</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <table class="table table-striped table-bordered table-sm diaryTableHeader">
        <thead>
        <tr>
            <th></th>
            <th style="width: 50px; text-align: center;">Вес</th>
            <th style="width: 44px; text-align: center;">Б</th>
            <th style="width: 44px; text-align: center;">Ж</th>
            <th style="width: 44px; text-align: center;">У</th>
            <th style="width: 50px; text-align: center;">Ккал</th>
            <th style="width: 35px; text-align: center;"></th>
        </tr>
        </thead>
    </table>
    @foreach($mealList as $key => $meal)
        <table class="table table-striped table-bordered table-sm diaryTable" id="diaryTable_{{$key}}">
            <thead class="thead-inverse">
            <tr>
                <th colspan="7">
                    <div class="pull-left product-add-div">
                        <input type="hidden" value="{{$key}}"/>
                        <i class="fa fa-plus product-add" aria-hidden="true" title="Добавить продукт или блюдо"></i>
                    </div>
                    <div style="text-align: center;">
                        {{$meal}}
                    </div>
                </th>
            </tr>
            </thead>
            <tbody>
            <tr id="diaryTableAmount_{{$key}}" class="diaryTableAmount" style="background-color: #fff;">
                <td>
                    <div class="font-weight-bold pull-right">ИТОГО:</div>
                </td>
                <td style="text-align: center; width: 50px; ">0</td>
                <td style="text-align: center; width: 44px; ">0</td>
                <td style="text-align: center; width: 44px; ">0</td>
                <td style="text-align: center; width: 44px;">0</td>
                <td style="text-align: center; width: 50px;">0</td>
                <td style="width: 35px;">
                </td>
            </tr>
            </tbody>
        </table>
    @endforeach
    <div class="form-row">
        <div class="form-group col-sm-10"></div>
        <div class="form-group col-sm-2">
            <button type="submit" style="float: right;" class="btn btn-secondary">Сохранить</button>
        </div>
    </div>
@endsection