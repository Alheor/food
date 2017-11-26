@extends('layout')
@section('content')
    <h4>Дневник питания на сегодня</h4>
    @for($i = 1; $i <= $numberOfMeals; $i++)
    <table class="table table-striped table-bordered table-sm">
        <thead class="thead-inverse">
        <tr>
            <td>
                Прием пищи № {{$i}}
            </td>
        </tr>
        <tr>
            <th>Продукт</th>
            <th style="width: 37px;">Вес</th>
            <th style="width: 37px;">Б</th>
            <th style="width: 37px;">Ж</th>
            <th style="width: 37px;">У</th>
            <th style="width: 37px;">Ккал</th>
            <th style="width: 35px;"></th>
        </tr>
        <tr>
            <td colspan="7">
                <i class="fa fa-plus product-add" aria-hidden="true"></i>
            </td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td style="padding-left: 10px;">
                <i class="fa fa-ban product-delete" aria-hidden="true"></i>
            </td>
        </tr>
        </tbody>
    </table>
    @endfor
    <div class="form-row">
        <div class="form-group col-sm-10"></div>
        <div class="form-group col-sm-2">
            <button type="submit" style="float: right;" class="btn btn-secondary">Сохранить</button>
        </div>
    </div>
@endsection