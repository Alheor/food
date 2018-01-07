@extends('layout')
@section('content')
    @if(isset($day))<input type="hidden" id="day_guid" value="{{$day->guid}}"/>@endif
    <div class="row">
        <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
            <div class="form-group">
                <label for="to_date">Дневник на</label>
                <div class="input-group date">
                    <input type="text" class="form-control" value="@if(isset($day))@date($day->to_date)@endif"@if(isset($day))disabled=""@endif id="to_date">
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
        <div class="col-6 col-sm-6 col-md-2 col-lg-2 col-xl-2">
            <div class="form-group">
                <label for="my_weight">Мой вес</label>
                <input type="text" id="my_weight" value="@if(isset($day)){{$day->my_weight}}@endif" placeholder="кг" class="form-control my-weight to-float"/>
            </div>
        </div>
        <div class="col-12 col-sm-12 col-md-6 col-lg-5 col-xl-4 ml-auto">
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
                    </tbody>
                </table>
            </div>
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

            @foreach($mealList as $key => $meal)
                <table class="table table-striped table-bordered table-sm diaryTable" id="diaryTable_{{$key}}">
                    <thead class="thead-inverse">
                    <tr>
                        <th colspan="7">
                            <div class="pull-left product-add-div">
                                <input type="hidden" value="{{$meal['guid']}}"/>
                                <i class="fa fa-plus product-add" aria-hidden="true" title="Добавить продукт или блюдо"></i>
                            </div>
                            <div style="text-align: center;">
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
                                    <td style="min-width: 40px;">
                                        <input type="integer" value="{{$product['w']}}" class="form-control input-table dishProdWeight">
                                    </td>
                                    <td style="background-color: #c3e6cb; text-align: center;">{{$product['b']}}</td>
                                    <td style="background-color: #ffeeba; text-align: center;">{{$product['j']}}</td>
                                    <td style="background-color: #f5c6cb; text-align: center;">{{$product['u']}}</td>
                                    <td style="text-align: center;">{{$product['k']}}</td>
                                    <td style="padding-left: 5px;">
                                        <i class="fa fa-ban product-delete" title="Удалить продукт или блюдо" onclick="if(confirm('Удалить?')){$(this).parent().parent().remove();calculateDiary();}" aria-hidden="true"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    <tr id="diaryTableAmount_{{$meal['guid']}}" class="diaryTableAmount" style="background-color: #fff;">
                        <td>
                            <div class="font-weight-bold pull-right">ИТОГО:</div>
                        </td>
                        <td style="text-align: center; width: 40px;">0</td>
                        <td style="text-align: center; width: 46px; font-weight: bold; background-color: #c3e6cb;">0</td>
                        <td style="text-align: center; width: 46px; font-weight: bold; background-color: #ffeeba;">0</td>
                        <td style="text-align: center; width: 46px; font-weight: bold; background-color: #f5c6cb;">0</td>
                        <td style="text-align: center; width: 50px; font-weight: bold; ">0</td>
                        <td style="width: 23px;">
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
            <div class="form-row">
                <div class="form-group col-5 col-sm-7 col-md-8 col-lg-9 col-xl-9"></div>
                <div class="form-group col-7 col-sm-5 col-md-4 col-lg-3 col-xl-3">
                    <div style="float: right;">
                        <input type="hidden" id="manufacturerToken" value="{{ csrf_token() }}" >
                        <button type="submit" style="float: right;" class="btn btn-secondary saveDiaryButton">Сохранить</button>
                    </div>
                    <div id="resultSendIndicator" style="float: right; margin-right: 10px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection