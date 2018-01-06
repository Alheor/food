@extends('layout')
@section('content')
    @if($form == 'new_form')
        <h2>Новое блюдо</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label for="prodName">Наименование <span class="text-danger font-weight-bold">*</span></label>
            <input type="text" value="{{ old('prodName') }}" autocomplete="off" class="form-control col-sm-12"
                   id="prodName" name="prodName">
        </div>
        <div class="form-group">
            <h5>Категория <span class="text-danger font-weight-bold">*</span></h5>
            @include('Dish.categories', ['tplName' => 'button'])
        </div>
        <div class="form-row">
            <div class="form-group col-xs-1">
                <label for="draft" class="col-form-label">Черновик</label>
                <input type="checkbox" @if(!empty(old('draft'))) checked="" @endif class="form-control" style="width: 30px; height: 30px;" id="draft"/>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <table class="table table-striped table-bordered table-sm diaryTableHeader">
                    <thead>
                    <tr>
                        <th></th>
                        <th style="width: 50px; text-align: center;">Вес</th>
                        <th style="width: 44px; text-align: center;">Б</th>
                        <th style="width: 44px; text-align: center;">Ж</th>
                        <th style="width: 44px; text-align: center;">У</th>
                        <th style="width: 40px; text-align: center;">Ккал</th>
                        <th style="width: 35px; text-align: center;"></th>
                    </tr>
                    </thead>
                </table>
                <table class="table table-striped table-bordered table-sm diaryTable" id="dishTable">
                    <thead class="thead-inverse">
                    <tr>
                        <th colspan="7">
                            <div class="pull-left dish-add-div">
                                <i class="fa fa-plus product-add" aria-hidden="true"
                                   title="Добавить продукт или блюдо"></i>
                            </div>
                            <div style="text-align: center;">Ингредиенты</div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id="dishTableAmount" class="diaryTableAmount" style="background-color: #fff;">
                        <td>
                            <div class="font-weight-bold pull-right">ИТОГО:</div>
                        </td>
                        <td style="text-align: center; width: 40px;">0</td>
                        <td style="text-align: center; width: 46px; font-weight: bold; background-color: #c3e6cb;">0
                        </td>
                        <td style="text-align: center; width: 46px; font-weight: bold; background-color: #ffeeba;">0
                        </td>
                        <td style="text-align: center; width: 46px; font-weight: bold; background-color: #f5c6cb;">0
                        </td>
                        <td style="text-align: center; width: 50px; font-weight: bold; ">0</td>
                        <td style="width: 23px;">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-3">
                <label for="b" class="col-form-label">Вес готового блюда, гр.
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                       title="Получившийся вес блюда, после его приготовления. По умолчанию равен весу всех ингредиентов."></i>
                </label>
                <input type="text" value="{{ old('b') }}" autocomplete="off" class="form-control calc_bju_field" id="dish_weight"/>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-12">
                <label for="comment" class="col-form-label">Комментарий</label>
                <textarea class="form-control" id="comment">{{ old('comment') }}</textarea>
            </div>
        </div>

        <div class="form-group" id="suitable_for">
            <h5>Подходит для <span class="text-danger font-weight-bold">*</span></h5>
            <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                <label class="btn" style="background-color: #7ed7d4;">
                    <input class="my-input-checkbox" @if(old('sh')) checked="" @endif type="checkbox" id="sh"
                           autocomplete="off">Сушка
                </label>
            </div>
            <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                <label class="btn" style="background-color: #81d877;">
                    <input class="my-input-checkbox" @if(old('ph')) checked="" @endif type="checkbox" id="ph"
                           autocomplete="off">Похудение
                </label>
            </div>
            <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                <label class="btn" style="background-color: #f2d638;">
                    <input class="my-input-checkbox" @if(old('pd')) checked="" @endif type="checkbox" id="pd"
                           autocomplete="off">Поддержка
                </label>
            </div>
            <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                <label class="btn" style="background-color: #eb9a53;">
                    <input class="my-input-checkbox" @if(old('nm')) checked="" @endif type="checkbox" id="nm"
                           autocomplete="off">Набор массы
                </label>
            </div>
            <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                <label class="btn" style="background-color: #e66161;">
                    <input class="my-input-checkbox" @if(old('cm')) checked="" @endif type="checkbox" id="cm"
                           autocomplete="off">Cheat meal
                </label>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-sm-10"></div>
            <div class="form-group col-sm-2">
                <input type="hidden" id="manufacturerToken" value="{{ csrf_token() }}" >
                <button type="submit" style="float: right;" class="btn btn-secondary" id="create_dish">Создать продукт</button>
            </div>
        </div>
    @endif

    @if($form == 'success_form')
        <h2 class="text-success">Продукт успешно создан!</h2>
    @endif
@endsection