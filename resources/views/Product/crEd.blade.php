@extends('layout')
@section('content')
    <?php
    if (isset($product) && !$copy) {
        $pageTitle = $product->name;
    } else {
        $pageTitle = 'Новый продукт';
    }
    ?>
    @if($form == 'new_form')
        <form method="post" id="product_form">
            @if ($errors->any())
                <div class="alert alert-danger errors-div">
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
                <input type="text" required
                       @if(old('prodName')) value="{{ old('prodName') }}"
                       @else @if(isset($product)) value="{{ $product->name }}" @endif @endif
                       autocomplete="off" class="form-control" id="prodName" name="prodName">
            </div>
            {{--<div class="form-group">--}}
            {{--<h5>Категория <span class="text-danger font-weight-bold">*</span></h5>--}}
            {{--@if(!isset($product))--}}
            {{--@include('Product.categories', ['tplName' => 'button'])--}}
            {{--@else--}}
            {{--@include('Product.categories', ['tplName' => 'button', 'product' => $product])--}}
            {{--@endif--}}
            {{--</div>--}}
            <div class="form-group">
                <h5>Торговая марка <span class="text-danger">*</span></h5>
                @if(!isset($product))
                    @include('Product.manufacturers', ['tplName' => 'button'])
                @else
                    @include('Product.manufacturers', ['tplName' => 'button', 'product' => $product])
                @endif
            </div>
            <h5 style="margin-bottom: 0;">Пищевая ценность на 100 грамм продукта</h5>
            <div class="form-row">
                <div class="form-group col-3">
                    <label for="b" class="col-form-label">Белки <span
                                class="text-danger font-weight-bold">*</span></label>
                    <input type="number" step="0.1" min="0" max="100" required
                           @if(old('b')) value="{{ old('b') }}" @else @if(isset($product)) value="{{ $product->b }}"
                           @endif @endif
                           autocomplete="off" class="form-control calc_bju_field b-bg-color" id="b" name="b"/>
                </div>
                <div class="form-group col-3">
                    <label for="j" class="col-form-label">Жиры <span
                                class="text-danger font-weight-bold">*</span></label>
                    <input type="number" step="0.1" min="0" max="100" required
                           @if(old('j')) value="{{ old('j') }}" @else @if(isset($product)) value="{{ $product->j }}"
                           @endif @endif
                           autocomplete="off" class="form-control calc_bju_field j-bg-color" id="j" name="j"/>
                </div>
                <div class="form-group col-3">
                    <label for="u" class="col-form-label">Угл. <span class="text-danger font-weight-bold">*</span>
                        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                           title="Углеводы, включая пищевые волокна."></i>
                    </label>
                    <input type="number" step="0.1" min="0" max="100" required
                           @if(old('u')) value="{{ old('u') }}" @else @if(isset($product)) value="{{ $product->u }}"
                           @endif @endif
                           autocomplete="off" class="form-control calc_bju_field  u-bg-color" id="u" name="u"/>
                </div>
                <div class="form-group col-3 cellulose">
                    <label for="cellulose" class="col-form-label" style="font-size: 14px; margin-bottom: 3px;">ПВ
                        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top"
                           title="Пищевые волокна."></i>
                    </label>
                    <input type="number" step="0.1" min="0" max="100" required
                           @if(old('cellulose')) value="{{ old('cellulose') }}"
                           @else  @if(isset($product)) value="{{ $product->cellulose }}" @endif value="0" @endif
                           autocomplete="off" class="form-control calc_bju_field" id="cellulose" name="cellulose"/>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-6">
                    <label for="k" class="col-form-label">Энергетическая ценность, Ккал</label>
                    <input type="text" readonly
                           @if(old('k')) value="{{ old('k') }}" @else @if(isset($product)) value="{{ $product->k }}"
                           @endif @endif
                           autocomplete="off" style="width: 100px;" class="form-control calc_bju_k_res" id="k" name="k"/>
                </div>
            </div>
            {{--<h5 style="margin: 0;">Добавки</h5>--}}
            {{--<div class="form-row">--}}
            {{--<div class="form-group col-xs-1">--}}
            {{--<label for="sugar" class="col-form-label">Сахар</label>--}}
            {{--<input type="checkbox"--}}
            {{--@if(old('sugar')) checked="" @else @if(isset($product) && $product->sugar == 1) checked="" @endif @endif--}}
            {{--class="form-control" style="width: 30px; height: 30px;" id="sugar" name="sugar"/>--}}
            {{--</div>--}}
            {{--<div class="form-group col-xs-1">--}}
            {{--<label for="salt" class="col-form-label">Соль</label>--}}
            {{--<input type="checkbox"--}}
            {{--@if(old('salt')) checked="" @else @if(isset($product) && $product->salt == 1) checked="" @endif @endif--}}
            {{--class="form-control" style="width: 30px; height: 30px;" id="salt" name="salt"/>--}}
            {{--</div>--}}
            {{--</div>--}}
            {{--<div class="form-group">--}}
            {{--<h5 style="margin-top: 0;">Подходит для <span class="text-danger font-weight-bold">*</span></h5>--}}
            {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
            {{--<label class="btn  @if(isset($product) && $product->attributes->food_sushka == 1) active @endif" style="background-color: #7ed7d4;">--}}
            {{--<input class="my-input-checkbox"--}}
            {{--@if(old('sh')) checked="" @else @if(isset($product) && $product->attributes->food_sushka == 1) checked="" @endif @endif--}}
            {{--type="checkbox" name="sh" autocomplete="off">Сушка--}}
            {{--</label>--}}
            {{--</div>--}}
            {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
            {{--<label class="btn @if(isset($product) && $product->attributes->food_pohudenie == 1) active @endif" style="background-color: #81d877;">--}}
            {{--<input class="my-input-checkbox"--}}
            {{--@if(old('ph')) checked="" @else @if(isset($product) && $product->attributes->food_pohudenie == 1) checked="" @endif @endif--}}
            {{--type="checkbox" name="ph" autocomplete="off">Похудение--}}
            {{--</label>--}}
            {{--</div>--}}
            {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
            {{--<label class="btn @if(isset($product) && $product->attributes->food_podderjka == 1) active @endif" style="background-color: #f2d638;">--}}
            {{--<input class="my-input-checkbox"--}}
            {{--@if(old('pd')) checked="" @else @if(isset($product) && $product->attributes->food_podderjka == 1) checked="" @endif @endif--}}
            {{--type="checkbox" name="pd" autocomplete="off">Поддержка--}}
            {{--</label>--}}
            {{--</div>--}}
            {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
            {{--<label class="btn  @if(isset($product) && $product->attributes->food_nabor_massi == 1) active @endif" style="background-color: #eb9a53;">--}}
            {{--<input class="my-input-checkbox"--}}
            {{--@if(old('nm')) checked="" @else @if(isset($product) && $product->attributes->food_nabor_massi == 1) checked="" @endif @endif--}}
            {{--type="checkbox" name="nm" autocomplete="off">Набор массы--}}
            {{--</label>--}}
            {{--</div>--}}
            {{--<div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">--}}
            {{--<label class="btn @if(isset($product) && $product->attributes->food_cheat_meal == 1) active @endif" style="background-color: #e66161;">--}}
            {{--<input class="my-input-checkbox"--}}
            {{--@if(old('cm')) checked="" @else @if(isset($product) && $product->attributes->food_cheat_meal == 1) checked="" @endif @endif--}}
            {{--type="checkbox" name="cm"  autocomplete="off">Cheat meal--}}
            {{--</label>--}}
            {{--</div>--}}
            {{--</div>--}}
            <div class="form-row main-footer-box">
                <div class="form-group col-sm-12">
                    <button type="submit" style="float: right;" class="btn btn-success" id="submit_button">
                        @if(!isset($product))
                            Создать
                        @else
                            Сохранить
                        @endif
                    </button>
                </div>
            </div>
        </form>
        @if(isset($product) && !$copy)
            <script type="application/javascript">
                $('#product_form').on('submit', function () {
                    $('#submit_button')[0].disabled = true;

                    if (confirm('Внимание! Изменение БЖУ продукта повлияет на всю историю статистики. Продолжить?')) {
                        return true;
                    } else {
                        $('#submit_button')[0].disabled = false;
                        return false;
                    }
                });
            </script>
        @else
            <script type="application/javascript">
                $('#product_form').on('submit', function () {
                    $('#submit_button')[0].disabled = true;
                });
            </script>
        @endif
    @endif
@endsection