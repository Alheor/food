@extends('layout')
@section('content')
    @php $pageTitle = 'Дневник питания'; @endphp
    @if(isset($day) && !$copy)<input type="hidden" id="day_guid" value="{{$day->guid}}"/>@endif
    @if($copy)<input type="hidden" id="day_copy"/>@endif
    @if(!empty($new))<input type="hidden" id="day_new"/>@endif

    <div class="row">
        <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="form-group">
                <label for="to_date" style="float:left; margin-top: 5px; margin-right: 10px;">Дата</label>
                <div class="input-group date">
                    <input type="text" @if(!$copy)placeholder="Сегодня" @endif class="form-control" value="@if(isset($day) && !$copy)@date($day->to_date)@endif"@if(isset($day) && !$copy)disabled=""@endif id="to_date">
                    <span class="input-group-addon">
                        <i class="fa fa-calendar" aria-hidden="true" style="cursor: pointer;"></i>
                    </span>
                </div>
                <script type="application/javascript">
                    $('.input-group.date').datepicker({
                        language: "ru",
                        todayHighlight: true,
                        daysOfWeekHighlighted: "0,6"
                    });
                </script>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-4 ml-auto">
            <div>
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
                        <td style="width: 50px; text-align: center;">Вес, гр.</td>
                        <td style="width: 44px; text-align: center;">Белки, гр.</td>
                        <td style="width: 44px; text-align: center;">Жиры, гр.</td>
                        <td style="width: 44px; text-align: center;">Угл., гр.</td>
                        <td style="width: 50px; text-align: center;">Ккал.</td>
                    </tr>
                    <tr>
                        <td style="width: 50px; text-align: center;">@if(isset($day)) {{$day->w}} @else 0 @endif</td>
                        <td style="width: 44px; text-align: center; background-color: #c3e6cb;" class="resultB">@if(isset($day)) {{$day->b}} @else 0 @endif</td>
                        <td style="width: 44px; text-align: center; background-color: #ffeeba; " class="resultJ">@if(isset($day)) {{$day->j}} @else 0 @endif</td>
                        <td style="width: 44px; text-align: center; background-color: #f5c6cb;" class="resultU">@if(isset($day)) {{$day->u}} @else 0 @endif</td>
                        <td style="width: 50px; text-align: center;" class="resultK">@if(isset($day)) {{$day->k}} @else 0 @endif</td>
                    </tr>
                    <tr>
                        <td style="width: 50px; text-align: center;"></td>
                        <td style="width: 44px; text-align: center; background-color: #c3e6cb;" class="resultB">0%</td>
                        <td style="width: 44px; text-align: center; background-color: #ffeeba;" class="resultJ">0%</td>
                        <td style="width: 44px; text-align: center; background-color: #f5c6cb;" class="resultU">0%</td>
                        <td style="width: 50px; text-align: center;"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <table class="table table-striped table-bordered table-sm diaryTableHeader diaryTable">
                <thead>
                <tr>
                    <th></th>
                    <th class="weight-diary">Вес</th>
                    <th style="width: 34px;">Б</th>
                    <th style="width: 34px;">Ж</th>
                    <th style="width: 34px;">У</th>
                    <th style="width: 34px;">Ккал</th>
                    <th style="width: 25px;"></th>
                </tr>
                </thead>
            </table>

            @foreach($mealList as $key => $meal)
                <table class="table table-striped table-bordered table-sm diaryTable" id="diaryTable_{{$key}}">
                    <thead class="thead-inverse">
                    <tr>
                        <th colspan="7" style="padding-left: 4px;">
                            <div class="pull-left product-add-div">
                                <input type="hidden" value="{{$meal['guid']}}"/>
                                <i class="fa fa-plus product-add" aria-hidden="true" title="Добавить продукт или блюдо"></i>
                            </div>
                            <div class="pull-left" style="text-align: center; padding-left: 1px;">
                                {{$meal['name']}}
                            </div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(isset($data[$meal['guid']]))
                            @foreach($data[$meal['guid']] as $product)
                                <tr>
                                    <td style="text-overflow: ellipsis;">
                                        <input type="hidden" value="{{$product['source']}}">
                                        <a tabindex="0" role="button" data-trigger="focus" class="dish-prod-info" data-toggle="dish-prod-info_{{$product['guid']}}" data-original-title="" title="">{{$product['source']->name}}</a>
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
                                    <td style="background-color: #c3e6cb; text-align: center;">{{$product['b']}}</td>
                                    <td style="background-color: #ffeeba; text-align: center;">{{$product['j']}}</td>
                                    <td style="background-color: #f5c6cb; text-align: center;">{{$product['u']}}</td>
                                    <td style="text-align: center;">{{$product['k']}}</td>
                                    <td>
                                        <i class="fa fa-ban product-delete" title="Удалить продукт или блюдо" aria-hidden="true"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    <tr id="diaryTableAmount_{{$meal['guid']}}" class="diaryTableAmount" style="background-color: #fff;">
                        <td>
                            <div class="font-weight-bold pull-right" style="padding-right: 5px;">ИТОГО:</div>
                        </td>
                        <td class="weight-diary-total" style="width: 38px; height: 25px;">0</td>
                        <td style="text-align: center; width: 34px; font-weight: bold; background-color: #c3e6cb;">0</td>
                        <td style="text-align: center; width: 34px; font-weight: bold; background-color: #ffeeba;">0</td>
                        <td style="text-align: center; width: 34px; font-weight: bold; background-color: #f5c6cb;">0</td>
                        <td style="text-align: center; width: 34px; font-weight: bold; ">0</td>
                        <td style="width: 25px;">
                        </td>
                    </tr>
                    </tbody>
                </table>
            @endforeach

            <script type="application/javascript">
                $(document).ready(function () {
                    $('.dishProdWeight').keyup(function (event) {
                        this.value = this.value.replace(/[^0-9]*/g, '');
                        setTimeout(function () {
                            calculateDiary();
                        }, 100);

                    });

                    calculateDiary();
                });
            </script>
        </div>
    </div>
    <div class="row main-footer-box">
        <div class="col-5 col-sm-7 col-md-8 col-lg-9 col-xl-9"></div>
        <div class="col-7 col-sm-5 col-md-4 col-lg-3 col-xl-3">
            <div style="float: right;">
                <input type="hidden" id="form_token" value="{{ csrf_token() }}" >
                <button type="submit" style="float: right;" class="btn btn-success saveDiaryButton">Сохранить</button>
            </div>
        </div>
    </div>
    <div style="display: none;" id="dpa_form" data-guid="">
        {{--<div class="modal-block"></div>--}}
        <div class="col-12">
            <div class="row">
                <div class="col-12 col-xl-12 input-group" style="padding: 1px;">
                    <input type="hidden" class="meal-guid"/>
                    <div class="input-group">
                        <div class="input-group-prepend select-search-type">
                            <button class="btn btn-outline-secondary dropdown-toggle search_type" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-content="all-all">Всё</button>
                            <div class="dropdown-menu"  style="font-size: 14px;">
                                <a class="dropdown-item" href="#"
                                   onclick="$(this).parent().parent().find('button').data('content', 'all-all');dishProdSearch();"
                                >Всё</a>
                                <a class="dropdown-item" href="#"
                                   onclick="$(this).parent().parent().find('button').data('content', 'all-dishes');dishProdSearch();"
                                >Все блюда</a>
                                <a class="dropdown-item" href="#"
                                   onclick="$(this).parent().parent().find('button').data('content', 'all-products');dishProdSearch();"
                                >Все продукты</a>
                                <a class="dropdown-item" href="#"
                                   onclick="$(this).parent().parent().find('button').data('content', 'my-dishes');dishProdSearch();"
                                >Только мои блюда</a>
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
        <table class="table table-bordered table-sm" style="margin-bottom: 0;">
            <thead class="thead-inverse">
            <tr>
                <th scope="col" style="font-size: 14px; font-weight: normal;">Наименование</th>
                <th class="weight" style="padding-bottom: 3px !important; padding-top: 0 !important; text-align: center; font-size: 14px; font-weight: normal;">Вес</th>
                <th style="width: 132px; font-size: 14px; font-weight: normal;">
                    <div class="prod-search-el">Б</div>
                    <div class="prod-search-el">Ж</div>
                    <div class="prod-search-el">У</div>
                    <div class="prod-search-el" style="margin-right: 0px !important; width: 25px; font-size: 12px; margin-top: 1px;">Ккал</div>
                </th>
            </tr>
            </thead>
        </table>
        <div style="max-height: 190px; overflow: auto;">
            <table class="table table-bordered table-sm" style="margin-bottom: 0;">
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
                <div class="form-group pull-right"  style="margin-bottom: 0;">
                    <a href="{{ route('new_dish', ['new']) }}" target="_blank" class="btn btn-success">Создать блюдо</a>
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
                <div class="form-group pull-right"  style="margin-bottom: 0;">
                    <a href="{{ route('new_dish', ['new']) }}" target="_blank" class="btn btn-success">Создать блюдо</a>
                </div>
            </div>
        </div>
    </div>
@endsection