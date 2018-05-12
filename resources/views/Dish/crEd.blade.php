@extends('layout')
@section('content')
    <?php
    if (isset($dish) && !$copy) {
        $pageTitle = $dish->name;
    } else {
        $pageTitle = 'Новое блюдо';
    }
    ?>
    <div id="dish_guid_div">
        @if(isset($dish) &&!$copy)<input type="hidden" id="dish_guid" value="{{$dish->guid}}"/>@endif
        @if($copy)<input type="hidden" id="dish_copy" value="1"/>@endif
    </div>
    <div class="row">
        <div class="col-11">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group" style="margin-top: 5px;">
                <label for="prodName">Наименование <span class="text-danger font-weight-bold">*</span></label>
                <input type="text" value="@if($dish){{$dish->name}}@endif" autocomplete="off" class="form-control"
                       id="prodName" name="prodName">
            </div>
        </div>
        <div class="col-1">
            <label class="btn pull-right btn-dark @if(!$dish || $dish && $dish->draft === 1)active @endif" style="margin-top: 37px;">
                <input class="my-input-checkbox" @if(!$dish || $dish && $dish->draft === 1)checked=""@endif type="checkbox" id="draft" autocomplete="off">Черновик
            </label>
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
        <div class="col">
            <table class="table table-striped table-bordered table-sm diaryTableHeader diaryTable">
                <thead>
                <tr>
                    <th></th>
                    <th class="weight-diary">Вес</th>
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
                            <td class="weight-diary">
                                <input type="number" value="{{$product['w']}}" class="form-control input-table dishProdWeight"/>
                            </td>
                            <td style="background-color: #c3e6cb;">{{$product['b']}}</td>
                            <td style="background-color: #ffeeba;">{{$product['j']}}</td>
                            <td style="background-color: #f5c6cb;">{{$product['u']}}</td>
                            <td>{{$product['k']}}</td>
                            <td>
                                <i class="fa fa-ban product-delete"  title="Удалить продукт"  onclick="if(confirm('Удалить?')){$(this).parent().parent().remove();calculateDish();}" aria-hidden="true"></i>
                            </td>
                        </tr>
                    @endforeach
                    <script type="application/javascript">
                        $(document).ready(function () {
                            $('.dishProdWeight').keyup(function (event) {
                                this.value = this.value.replace(/[^0-9]*/g, '');
                                setTimeout(function () {
                                    calculateDish();
                                }, 100);
                            });
                            calculateDish();
                        });
                    </script>
                @endif
                <tr id="dishTableAmount" class="dishTableAmount tabel-td" style="background-color: #fff;">
                    <td>
                        <div class="font-weight-bold pull-right" style="margin-right: 4px;">ИТОГО:</div>
                    </td>
                    <td class="weight-diary-total weight-diary" style="padding-top: 2px; font-size: 12px; font-weight: bold;" id="products_weight">0</td>
                    <td style="text-align: center; font-size: 12px; font-weight: bold; background-color: #c3e6cb;">0</td>
                    <td style="text-align: center; font-size: 12px; font-weight: bold; background-color: #ffeeba;">0</td>
                    <td style="text-align: center; font-size: 12px; font-weight: bold; background-color: #f5c6cb;">0</td>
                    <td style="text-align: center; font-size: 12px; font-weight: bold;">0</td>
                    <td style="width: 25px;"></td>
                </tr>
                <tr id="dishTableAmountPer100" class="dishTableAmountPer100 tabel-td" style="background-color: #fff;">
                    <td>
                        <div class="font-weight-bold pull-right" style="margin-right: 4px;"></div>
                    </td>
                    <td class="weight-diary-total">100</td>
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
        <div class="col-7 col-sm-5 col-md-4 col-lg-3 col-xl-3">
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
    {{--<div class="form-group" id="suitable_for">--}}
    {{--<div class="col">--}}
    {{--<h5>Подходит для <span class="text-danger font-weight-bold">*</span></h5>--}}
    {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
    {{--<label class="btn @if($dish && $dish->attributes->food_sushka)) active @endif" style="background-color: #7ed7d4;">--}}
    {{--<input class="my-input-checkbox" @if($dish && $dish->attributes->food_sushka)) checked="" @endif type="checkbox" id="sh"--}}
    {{--autocomplete="off">Сушка--}}
    {{--</label>--}}
    {{--</div>--}}
    {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
    {{--<label class="btn @if($dish && $dish->attributes->food_pohudenie)) active @endif" style="background-color: #81d877;">--}}
    {{--<input class="my-input-checkbox" @if($dish && $dish->attributes->food_pohudenie)) checked="" @endif type="checkbox" id="ph"--}}
    {{--autocomplete="off">Похудение--}}
    {{--</label>--}}
    {{--</div>--}}
    {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
    {{--<label class="btn @if($dish && $dish->attributes->food_podderjka)) active @endif" style="background-color: #f2d638;">--}}
    {{--<input class="my-input-checkbox" @if($dish && $dish->attributes->food_podderjka)) checked="" @endif type="checkbox" id="pd"--}}
    {{--autocomplete="off">Поддержка--}}
    {{--</label>--}}
    {{--</div>--}}
    {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
    {{--<label class="btn @if($dish && $dish->attributes->food_nabor_massi)) active @endif" style="background-color: #eb9a53;">--}}
    {{--<input class="my-input-checkbox" @if($dish && $dish->attributes->food_nabor_massi)) checked="" @endif type="checkbox" id="nm"--}}
    {{--autocomplete="off">Набор массы--}}
    {{--</label>--}}
    {{--</div>--}}
    {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
    {{--<label class="btn @if($dish && $dish->attributes->food_cheat_meal)) active @endif" style="background-color: #e66161;">--}}
    {{--<input class="my-input-checkbox" @if($dish && $dish->attributes->food_cheat_meal)) checked="" @endif type="checkbox" id="cm"--}}
    {{--autocomplete="off">Cheat meal--}}
    {{--</label>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    <div class="row main-footer-box">
        <div class="form-group col-5 col-sm-7 col-md-8 col-lg-9 col-xl-9"></div>
        <div class="form-group col-7 col-sm-5 col-md-4 col-lg-3 col-xl-3">
            <div style="float: right;">
                <input type="hidden" id="form_token" value="{{ csrf_token() }}" >
                <button type="submit" style="float: right;" class="btn btn-success" id="create_dish">
                    @if($dish)Сохранить@elseСоздать@endif
                </button>
            </div>
        </div>
    </div>
    <div style="display: none;" id="dpa_form" data-guid="">
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-xl-12 input-group" style="padding: 1px;">
                    <input type="hidden" class="meal-guid"/>
                    <div class="input-group">
                        <div class="input-group-prepend select-search-type">
                            <button class="btn btn-outline-secondary dropdown-toggle search_type" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-content="all-products">Все продукты</button>
                            <div class="dropdown-menu"  style="font-size: 14px;">
                                <a class="dropdown-item" href="#"
                                   onclick="$(this).parent().parent().find('button').data('content', 'all-products');dishProdSearch();"
                                >Все продукты</a>
                                <a class="dropdown-item" href="#"
                                   onclick="$(this).parent().parent().find('button').data('content', 'my-products');dishProdSearch();"
                                >Только мои продукты</a>
                            </div>
                        </div>
                        <input class="form-control pull-left dishProdSearch" onkeyup="dishProdSearch();" type="text" placeholder="Найти" aria-label="Найти" autocomplete="off">
                        <button class="btn btn-outline-danger dpa-form-reset"
                                name="clear"
                                title="Сброс"
                                type="submit"
                                style=" border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="manufacturers-filter"><div class="badge badge-pill badge-warning all" onclick="manufacturerFilter(this)" >Любой</div></div>
        <table class="table table-striped table-bordered table-sm" style="margin-bottom: 0;">
            <thead class="thead-inverse">
            <tr>
                <th scope="col" style="font-size: 14px; font-weight: normal;">Наименование</th>
                <th class="weight" style="text-align: center; font-size: 14px; font-weight: normal;">Вес</th>
                <th style="width: 132px; font-size: 14px; font-weight: normal;">
                    <div class="prod-search-el">Б</div>
                    <div class="prod-search-el">Ж</div>
                    <div class="prod-search-el">У</div>
                    <div class="prod-search-el" style="margin-right: 0px !important; width: 25px; font-size: 12px; margin-top: 1px;">Ккал</div>
                </th>
            </tr>
            </thead>
        </table>
        <div style="max-height: 218px; overflow: auto;">
            <table class="table table-striped table-bordered table-sm" style="margin-bottom: 0;">
                <tbody class="dish-prod-list">
                </tbody>
            </table>
        </div>
    </div>
    <div style="display: none;" id="dpa_empty_form">
        <div class="row">
            <div class="form-group col-6" style="margin-bottom: 0;">
                Ничего не найдено :(
            </div>
            <div class="form-group col-6" style="margin-bottom: 0;">
                <div class="form-group pull-right" style="margin-bottom: 5px;">
                    <a href="{{ route('new_product', ['new']) }}" target="_blank" class="btn btn-info">Создать продукт</a>
                </div>
            </div>
        </div>
    </div>
    <div style="display: none;" id="dpa_start_form">
        <div class="row">
            <div class="form-group col-6" style="margin-bottom: 0;">
                Введите слово для поиска...
            </div>
            <div class="form-group col-6" style="margin-bottom: 0;">
                <div class="form-group pull-right" style="margin-bottom: 5px;">
                    <a href="{{ route('new_product', ['new']) }}" target="_blank" class="btn btn-info">Создать продукт</a>
                </div>
            </div>
        </div>
    </div>
@endsection