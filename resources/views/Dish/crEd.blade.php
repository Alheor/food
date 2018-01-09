@extends('layout')
@section('content')
    <div id="dish_guid_div">
        @if(isset($dish))<input type="hidden" id="dish_guid" value="{{$dish->guid}}"/>@endif
    </div>
        <div class="row">
            <div class="col-8">
                <h2>Новое блюдо</h2>
            </div>
            <div class="col-4">
                <div class="btn-group pull-right" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn btn-dark @if(!$dish || $dish && $dish->draft === 1)active @endif">
                        <input class="my-input-checkbox" @if(!$dish || $dish && $dish->draft === 1)checked=""@endif type="checkbox" id="draft" autocomplete="off">Черновик
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="form-group">
                    <label for="prodName">Наименование <span class="text-danger font-weight-bold">*</span></label>
                    <input type="text" value="@if($dish){{$dish->name}}@endif" autocomplete="off" class="form-control"
                           id="prodName" name="prodName">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col">
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
                <table class="table table-striped table-bordered table-sm diaryTableHeader diaryTable">
                    <thead>
                    <tr>
                        <th></th>
                        <th style="width: 37px;">Вес</th>
                        <th style="width: 37px;">Б</th>
                        <th style="width: 37px;">Ж</th>
                        <th style="width: 37px;">У</th>
                        <th style="width: 37px;">Ккал</th>
                        <th style="width: 25px;"></th>
                    </tr>
                    </thead>
                </table>
                <table class="table table-striped table-bordered table-sm diaryTable" style="margin-bottom: 0;" id="dishTable">
                    <thead class="thead-inverse">
                    <tr>
                        <th colspan="7" style="padding-left: 4px;">
                            <div class="pull-left dish-add-div">
                                <i class="fa fa-plus product-add" aria-hidden="true"
                                   title="Добавить продукт или блюдо"></i>
                            </div>
                            <div class="pull-left" style="text-align: center; padding-left: 1px;">Ингредиенты</div>
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
                                    <td style="background-color: #c3e6cb;">{{$product['b']}}</td>
                                    <td style="background-color: #ffeeba;">{{$product['j']}}</td>
                                    <td style="background-color: #f5c6cb;">{{$product['u']}}</td>
                                    <td>{{$product['k']}}</td>
                                    <td>
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
                                <div class="font-weight-bold pull-right" style="margin-right: 4px;">ИТОГО на 100 гр.</div>
                            </td>
                            <td style="text-align: center; width: 37px;" id="products_weight">0</td>
                            <td style="text-align: center; width: 37px; font-weight: bold; background-color: #c3e6cb;">0</td>
                            <td style="text-align: center; width: 37px; font-weight: bold; background-color: #ffeeba;">0</td>
                            <td style="text-align: center; width: 37px; font-weight: bold; background-color: #f5c6cb;">0</td>
                            <td style="text-align: center; width: 37px; font-weight: bold;">0</td>
                            <td style="width: 25px;"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-7 col-sm-5 col-md-4 col-lg-3 col-xl-3">
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
                <textarea class="form-control" id="comment" style="height: 100px;">@if($dish){{$dish->comment}}@endif</textarea>
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
        <div class="row">
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