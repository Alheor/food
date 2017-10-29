@extends('layout')
@section('content')
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
            <input type="text" class="form-control col-sm-12" id="prodName" name="prodName">
        </div>
        <h5>Пищевая ценность в граммах на 100 грамм продукта</h5>
        <div class="form-row">
            <div class="form-group col-sm-2">
                <label for="b" class="col-form-label">Белки</label>
                <input type="number" class="form-control" id="b" name="b"/>
            </div>
            <div class="form-group col-sm-2">
                <label for="j" class="col-form-label">Жиры</label>
                <input type="number" class="form-control" id="j" name="j"/>
            </div>
            <div class="form-group col-sm-2">
                <label for="u" class="col-form-label">Углеводы</label>
                <input type="number" class="form-control" id="u" name="u"/>
            </div>
            <div class="form-group col-sm-2">
                <label for="k" class="col-form-label">Ккал</label>
                <input type="number" class="form-control" id="k" name="k"/>
            </div>
            <div class="form-group col-sm-2">
                <label for="sugar" class="col-form-label">Сахар</label>
                <input type="number" class="form-control" id="sugar" name="sugar"/>
            </div>
            <div class="form-group col-sm-2">
                <label for="salt" class="col-form-label">Соль</label>
                <input type="number" class="form-control" id="salt" name="salt"/>
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
                <label class="btn btn-success">
                    <input class="my-input-checkbox" type="checkbox" autocomplete="off">Похудение
                </label>
            </div>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-warning">
                    <input class="my-input-checkbox" type="checkbox" autocomplete="off">Поддержка
                </label>
            </div>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-danger">
                    <input class="my-input-checkbox" type="checkbox" autocomplete="off">Набор массы
                </label>
            </div>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-primary">
                    <input class="my-input-checkbox" type="checkbox" autocomplete="off">Cheat meal
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
@endsection