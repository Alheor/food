@extends('layout')
@section('content')
    @if(isset($dish))<input type="hidden" id="dish_guid" value="{{$dish->guid}}"/>@endif
        <div class="row">
            <div class="col">
                <h2>Новое блюдо</h2>
            </div>
            <div class="col">
                <div class="btn-group pull-right" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn btn-dark @if(!$dish || $dish && $dish->draft === 1)active @endif">
                        <input class="my-input-checkbox" @if(!$dish || $dish && $dish->draft === 1)checked=""@endif type="checkbox" id="draft" autocomplete="off">Черновик
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
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
                    <input type="text" value="@if($dish){{$dish->name}}@endif" autocomplete="off" class="form-control col-sm-12"
                           id="prodName" name="prodName">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h5>Категория <span class="text-danger font-weight-bold">*</span></h5>
                @if($dish)
                    @include('Dish.categories', [
                        'tplName' => 'button',
                        'category_name' => $dish->dishCategory->name,
                        'category' => $dish->dishCategory->id
                    ])
                @else
                    @include('Dish.categories', ['tplName' => 'button'])
                @endif
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
                <table class="table table-striped table-bordered table-sm diaryTableHeader">
                    <thead>
                    <tr>
                        <th></th>
                        <th style="width: 42px; text-align: center; padding: .1rem;">Вес</th>
                        <th style="width: 40px; text-align: center; padding: .1rem;">Б</th>
                        <th style="width: 40px; text-align: center; padding: .1rem;">Ж</th>
                        <th style="width: 40px; text-align: center; padding: .1rem;">У</th>
                        <th style="width: 40px; text-align: center; padding: .1rem;">Ккал</th>
                        <th style="width: 23px; text-align: center;"></th>
                    </tr>
                    </thead>
                </table>
                <table class="table table-striped table-bordered table-sm diaryTable" style="margin-bottom: 0px;" id="dishTable">
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
                        @if(isset($productList))
                            @foreach($productList as $product)
                                <tr class="tabel-td">
                                    <td style="text-overflow: ellipsis; padding-left: 5px;">
                                    <input type="hidden" value='{{$product['source']}}'/>
                                        <a tabindex="0"  role="button" data-trigger="focus" class="dish-prod-info" data-toggle="dish-prod-info_{{$product['guid']}}">{{$product['source']->name}}</a>
                                        <script type="application/javascript">
                                            setTimeout(function () {
                                                $('[data-toggle="dish-prod-info_{{$product['guid']}}"]').popover({
                                                    trigger: 'focus',
                                                    html: true,
                                                    content: dishProdInfo({!!json_encode($product['source'])!!})
                                                });
                                            }, 100);
                                        </script>
                                    </td>
                                    <td style="min-width: 42px;">
                                        <input type="integer" value="{{$product['w']}}" class="form-control input-table dishProdWeight"/>
                                    </td>
                                    <td style="background-color: #c3e6cb; text-align: center;">{{$product['b']}}</td>
                                    <td style="background-color: #ffeeba; text-align: center;">{{$product['j']}}</td>
                                    <td style="background-color: #f5c6cb; text-align: center;">{{$product['u']}}</td>
                                    <td style="text-align: center;">{{$product['k']}}</td>
                                    <td style="padding-left: 5px;">
                                        <i class="fa fa-ban product-delete"  title="Удалить продукт"  onclick="if(confirm('Удалить?')){$(this).parent().parent().remove();recalcDish();}" aria-hidden="true"></i>
                                    </td>
                                </tr>
                            @endforeach
                            <script type="application/javascript">
                                $(document).ready(function () {
                                    $('.dishProdWeight').keyup(function (event) {
                                        this.value = this.value.replace(/[^0-9]*/g, '');
                                        setTimeout(function () {
                                            recalcDish();
                                        }, 100);
                                    });
                                    recalcDish();
                                });
                            </script>
                        @endif
                        <tr id="dishTableAmount" class="diaryTableAmount tabel-td" style="background-color: #fff;">
                            <td>
                                <div class="font-weight-bold pull-right">ИТОГО:</div>
                            </td>
                            <td style="text-align: center; width: 42px; padding: .1rem;">0</td>
                            <td style="text-align: center; width: 40px; font-weight: bold; background-color: #c3e6cb; padding: .1rem;">0
                            </td>
                            <td style="text-align: center; width: 40px; font-weight: bold; background-color: #ffeeba; padding: .1rem;">0
                            </td>
                            <td style="text-align: center; width: 40px; font-weight: bold; background-color: #f5c6cb; padding: .1rem;">0
                            </td>
                            <td style="text-align: center; width: 40px; font-weight: bold; padding: .1rem;">0</td>
                            <td style="width: 23px;">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-5 col-sm-5 col-md-4 col-lg-3 col-xl-3">
                <label for="b" class="col-form-label">Вес готового блюда, гр.
                    <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                       title="Получившийся вес блюда, после его приготовления. По умолчанию равен весу всех ингредиентов."></i>
                </label>
                <input type="text" value="@if($dish){{$dish->weight_after}}@endif" autocomplete="off" class="form-control" id="dish_weight"/>
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
                <label for="comment" class="col-form-label">Комментарий</label>
                <textarea class="form-control" id="comment">@if($dish){{$dish->comment}}@endif</textarea>
            </div>
        </div>
        <div class="row" id="suitable_for">
            <div class="col">
                <h5>Подходит для <span class="text-danger font-weight-bold">*</span></h5>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn @if($dish && $dish->attributes->food_sushka)) active @endif" style="background-color: #7ed7d4;">
                        <input class="my-input-checkbox" @if($dish && $dish->attributes->food_sushka)) checked="" @endif type="checkbox" id="sh"
                               autocomplete="off">Сушка
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn @if($dish && $dish->attributes->food_pohudenie)) active @endif" style="background-color: #81d877;">
                        <input class="my-input-checkbox" @if($dish && $dish->attributes->food_pohudenie)) checked="" @endif type="checkbox" id="ph"
                               autocomplete="off">Похудение
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn @if($dish && $dish->attributes->food_podderjka)) active @endif" style="background-color: #f2d638;">
                        <input class="my-input-checkbox" @if($dish && $dish->attributes->food_podderjka)) checked="" @endif type="checkbox" id="pd"
                               autocomplete="off">Поддержка
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn @if($dish && $dish->attributes->food_nabor_massi)) active @endif" style="background-color: #eb9a53;">
                        <input class="my-input-checkbox" @if($dish && $dish->attributes->food_nabor_massi)) checked="" @endif type="checkbox" id="nm"
                               autocomplete="off">Набор массы
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn @if($dish && $dish->attributes->food_cheat_meal)) active @endif" style="background-color: #e66161;">
                        <input class="my-input-checkbox" @if($dish && $dish->attributes->food_cheat_meal)) checked="" @endif type="checkbox" id="cm"
                               autocomplete="off">Cheat meal
                    </label>
                </div>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-5 col-sm-7 col-md-8 col-lg-9 col-xl-9"></div>
            <div class="form-group col-7 col-sm-5 col-md-4 col-lg-3 col-xl-3">
                <div style="float: right;">
                    <input type="hidden" id="manufacturerToken" value="{{ csrf_token() }}" >
                    <button type="submit" style="float: right;" class="btn btn-secondary" id="create_dish">
                        @if($dish)Сохранить@elseСоздать продукт@endif
                    </button>
                </div>
                <div id="resultSendIndicator" style="float: right; margin-right: 10px;"></div>
            </div>
        </div>
@endsection