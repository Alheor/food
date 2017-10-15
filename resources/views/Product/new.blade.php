@extends('layout')
@section('content')
    <h2>Новый продукт</h2>
    <form>
        <div class="form-group">
            <label for="prodName">Наименование</label>
            <input type="text" class="form-control col-sm-12" id="prodName">
        </div>
        <h5>Пищевая ценность в граммах на 100 грамм продукта</h5>
        <div class="form-row">
            <div class="form-group col-sm-2">
                <label for="b" class="col-form-label">Белки</label>
                <input type="text" class="form-control" id="b">
            </div>
            <div class="form-group col-sm-2">
                <label for="j" class="col-form-label">Жиры</label>
                <input type="text" class="form-control" id="j">
            </div>
            <div class="form-group col-sm-2">
                <label for="u" class="col-form-label">Углеводы</label>
                <input type="text" class="form-control" id="u">
            </div>
            <div class="form-group col-sm-2">
                <label for="k" class="col-form-label">Ккал</label>
                <input type="text" class="form-control" id="k">
            </div>
            <div class="form-group col-sm-2">
                <label for="gi" class="col-form-label">Сахар</label>
                <input type="text" class="form-control" id="gi">
            </div>
            <div class="form-group col-sm-2">
                <label for="gi" class="col-form-label">Соль</label>
                <input type="text" class="form-control" id="gi">
            </div>
        </div>
        <div class="form-group">
            <h5>Категория</h5>
            @include('Product.categories')
        </div>
        <div class="form-group">
            <h5>Производитель</h5>
            @include('Product.manufacturers')
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