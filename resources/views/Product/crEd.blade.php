@extends('layout')
@section('content')
    @if($form == 'new_form')
        <div class="row">
            <div class="col-12 main-header-box">
                @if(!isset($product))
                    <h1>Новый продукт</h1>
                @else
                    <h1>Редактирование продукта</h1>
                @endif
            </div>
        </div>
        <form method="post" id="product_form">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <input type="hidden" name="_token" value="{{ csrf_token() }}" >
            <div class="form-group">
                <label for="prodName">Наименование <span class="text-danger font-weight-bold">*</span></label>
                <input
                        type="text"
                        @if(old('prodName')) value="{{ old('prodName') }}" @else @if(isset($product)) value="{{ $product->name }}" @endif @endif
                        autocomplete="off" class="form-control" id="prodName" name="prodName">
            </div>
            <div class="form-group">
                <h5>Категория <span class="text-danger font-weight-bold">*</span></h5>
                @if(!isset($product))
                    @include('Product.categories', ['tplName' => 'button'])
                @else
                    @include('Product.categories', ['tplName' => 'button', 'product' => $product])
                @endif
            </div>
            <div class="form-group">
                <h5>Торговая марка <span class="text-danger font-weight-bold">*</span></h5>
                @if(!isset($product))
                    @include('Product.manufacturers', ['tplName' => 'button'])
                @else
                    @include('Product.manufacturers', ['tplName' => 'button', 'product' => $product])
                @endif
            </div>
            <h5 style="margin-bottom: 0;">Пищевая ценность в граммах на 100 грамм продукта</h5>
            <div class="form-row">
                <div class="form-group col-sm-2">
                    <label for="b" class="col-form-label">Белки <span class="text-danger font-weight-bold">*</span></label>
                    <input type="text"
                           @if(old('b')) value="{{ old('b') }}" @else @if(isset($product)) value="{{ $product->b }}" @endif @endif
                           autocomplete="off" class="form-control calc_bju_field" id="b" name="b"/>
                </div>
                <div class="form-group col-sm-2">
                    <label for="j" class="col-form-label">Жиры <span class="text-danger font-weight-bold">*</span></label>
                    <input type="text"
                           @if(old('j')) value="{{ old('j') }}" @else @if(isset($product)) value="{{ $product->j }}" @endif @endif
                           autocomplete="off"  class="form-control calc_bju_field" id="j" name="j"/>
                </div>
                <div class="form-group col-sm-2">
                    <label for="u" class="col-form-label">Угл. <span class="text-danger font-weight-bold">*</span>
                        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Углеводы, включая клетчатку (пищевые волокна)."></i>
                    </label>
                    <input type="text"
                           @if(old('u')) value="{{ old('u') }}" @else @if(isset($product)) value="{{ $product->u }}" @endif @endif
                           autocomplete="off" class="form-control calc_bju_field" id="u" name="u"/>
                  </div>
                <div class="form-group col-sm-2 cellulose" @if(
                                                                old('category') != 1 &&
                                                                old('category') != 3 &&
                                                                old('category') != 6 &&
                                                                old('category') != 8 &&
                                                                old('category') != 9 &&
                                                                old('category') != 14 &&
                                                                old('category') != 15 &&
                                                                old('category') != 16 &&
                                                                old('category') != 31
                                                                ) style="display: none;" @endif>
                    <label for="cellulose" class="col-form-label">Клтч.<span class="text-danger font-weight-bold">*</span>
                        <i class="fa fa-question-circle" data-toggle="tooltip" data-placement="top" title="Клетчатка (пищевые волокна), при расчетах это значение вычитается из углеводов."></i>
                    </label>
                    <input type="text" autocomplete="off"
                           @if(old('cellulose')) value="{{ old('cellulose') }}" @else  @if(isset($product)) value="{{ $product->cellulose }}" @endif value="0" @endif
                           class="form-control calc_bju_field" id="cellulose" name="cellulose"/>
                </div>
                <div class="form-group col-sm-2">
                    <label for="k" class="col-form-label">Ккал <span class="text-danger font-weight-bold">*</span></label>
                    <input type="text"
                           @if(old('k')) value="{{ old('k') }}" @else @if(isset($product)) value="{{ $product->k }}" @endif @endif
                           autocomplete="off" class="form-control calc_bju_k_res" id="k" name="k"/>
                </div>
            </div>
            <h5 style="margin: 0;">Добавки</h5>
            <div class="form-row">
                <div class="form-group col-xs-1">
                    <label for="sugar" class="col-form-label">Сахар</label>
                    <input type="checkbox"
                           @if(old('sugar')) checked="" @else @if(isset($product) && $product->sugar == 1) checked="" @endif @endif
                           class="form-control" style="width: 30px; height: 30px;" id="sugar" name="sugar"/>
                </div>
                <div class="form-group col-xs-1">
                    <label for="salt" class="col-form-label">Соль</label>
                    <input type="checkbox"
                           @if(old('salt')) checked="" @else @if(isset($product) && $product->salt == 1) checked="" @endif @endif
                           class="form-control" style="width: 30px; height: 30px;" id="salt" name="salt"/>
                </div>
            </div>
            <div class="form-group">
                <h5 style="margin-top: 0;">Подходит для <span class="text-danger font-weight-bold">*</span></h5>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn  @if(isset($product) && $product->attributes->food_sushka == 1) active @endif" style="background-color: #7ed7d4;">
                        <input class="my-input-checkbox"
                               @if(old('sh')) checked="" @else @if(isset($product) && $product->attributes->food_sushka == 1) checked="" @endif @endif
                               type="checkbox" name="sh" autocomplete="off">Сушка
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn @if(isset($product) && $product->attributes->food_pohudenie == 1) active @endif" style="background-color: #81d877;">
                        <input class="my-input-checkbox"
                               @if(old('ph')) checked="" @else @if(isset($product) && $product->attributes->food_pohudenie == 1) checked="" @endif @endif
                               type="checkbox" name="ph" autocomplete="off">Похудение
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn @if(isset($product) && $product->attributes->food_podderjka == 1) active @endif" style="background-color: #f2d638;">
                        <input class="my-input-checkbox"
                               @if(old('pd')) checked="" @else @if(isset($product) && $product->attributes->food_podderjka == 1) checked="" @endif @endif
                               type="checkbox" name="pd" autocomplete="off">Поддержка
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn  @if(isset($product) && $product->attributes->food_nabor_massi == 1) active @endif" style="background-color: #eb9a53;">
                        <input class="my-input-checkbox"
                               @if(old('nm')) checked="" @else @if(isset($product) && $product->attributes->food_nabor_massi == 1) checked="" @endif @endif
                               type="checkbox" name="nm" autocomplete="off">Набор массы
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons" style="margin-bottom: 5px;">
                    <label class="btn @if(isset($product) && $product->attributes->food_cheat_meal == 1) active @endif" style="background-color: #e66161;">
                        <input class="my-input-checkbox"
                               @if(old('cm')) checked="" @else @if(isset($product) && $product->attributes->food_cheat_meal == 1) checked="" @endif @endif
                               type="checkbox" name="cm"  autocomplete="off">Cheat meal
                    </label>
                </div>
            </div>
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
        @if(isset($product))
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