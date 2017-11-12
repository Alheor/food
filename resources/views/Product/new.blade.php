@extends('layout')
@section('content')
    @if($form == 'new_form')
        <h2>Новый продукт</h2>
        <form method="post">
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
                <label for="prodName">Наименование</label>
                <input type="text" value="{{ old('prodName') }}" class="form-control col-sm-12" id="prodName" name="prodName">
            </div>
            <h5>Пищевая ценность в граммах на 100 грамм продукта</h5>
            <div class="form-row">
                <div class="form-group col-sm-2">
                    <label for="b" class="col-form-label">Белки</label>
                    <input type="text" value="{{ old('b') }}" class="form-control calc_bju_field" id="b" name="b"/>
                </div>
                <div class="form-group col-sm-2">
                    <label for="j" class="col-form-label">Жиры</label>
                    <input type="text" value="{{ old('j') }}" class="form-control calc_bju_field" id="j" name="j"/>
                </div>
                <div class="form-group col-sm-2">
                    <label for="u" class="col-form-label">Углеводы</label>
                    <input type="text" value="{{ old('u') }}" class="form-control calc_bju_field" id="u" name="u"/>
                </div>
                <div class="form-group col-sm-2">
                    <label for="k" class="col-form-label">Ккал</label>
                    <input type="text" value="{{ old('k') }}" class="form-control calc_bju_k_res" id="k" name="k"/>
                </div>
                <div class="form-group col-sm-2">
                    <label for="sugar" class="col-form-label">Сахар</label>
                    <input type="text" value="@if(empty(old('sugar'))) 0 @else {{ old('sugar') }} @endif" class="form-control" id="sugar" name="sugar"/>
                </div>
                <div class="form-group col-sm-2">
                    <label for="salt" class="col-form-label">Соль</label>
                    <input type="text" @if(empty(old('salt'))) value="0" @else value="{{ old('salt') }}" @endif class="form-control" id="salt" name="salt"/>
                </div>
            </div>
            <div class="form-group">
                <h5>Категория</h5>
                @include('Product.categories', ['tplName' => 'button'])
            </div>
            <div class="form-group">
                <h5>Производитель</h5>
                @include('Product.manufacturers', ['tplName' => 'button'])
            </div>
            <div class="form-group">
                <h5>Подходит для</h5>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn" style="background-color: #7ed7d4;">
                        <input class="my-input-checkbox" @if(old('sh')) checked="" @endif type="checkbox" name="sh" autocomplete="off">Сушка
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn"style="background-color: #81d877;">
                        <input class="my-input-checkbox" @if(old('ph')) checked="" @endif type="checkbox" name="ph" autocomplete="off">Похудение
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn" style="background-color: #f2d638;">
                        <input class="my-input-checkbox" @if(old('pd')) checked="" @endif type="checkbox" name="pd" autocomplete="off">Поддержка
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn" style="background-color: #eb9a53;">
                        <input class="my-input-checkbox"  @if(old('nm')) checked="" @endif type="checkbox" name="nm" autocomplete="off">Набор массы
                    </label>
                </div>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn" style="background-color: #e66161;">
                        <input class="my-input-checkbox" @if(old('cm')) checked="" @endif type="checkbox" name="cm"  autocomplete="off">Cheat meal
                    </label>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-sm-10"></div>
                <div class="form-group col-sm-2">
                    <button type="submit" style="float: right;" class="btn btn-secondary">Создать продукт</button>
                </div>
            </div>
        </form>
    @endif

    @if($form == 'success_form')
        <h2 class="text-success">Продукт успешно создан!</h2>
    @endif
@endsection