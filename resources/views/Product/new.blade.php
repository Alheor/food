@extends('layout')
@section('content')
    <h2>Новый продукт</h2>
    Категория
    <select>
        <option>1</option>
        <option>2</option>
        <option>3</option>
    </select><br/>
    Производитель
    <select>
        <option>1</option>
        <option>2</option>
        <option>3</option>
    </select><br/>
    Продается
    <select>
        <option>1</option>
        <option>2</option>
        <option>3</option>
    </select><br/>
    <form>
        <div class="form-group">
            <label for="prodName">Наименование</label>
            <input type="text" class="form-control col-sm-12" id="prodName">
        </div>
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
                <label for="gi" class="col-form-label">ГИ</label>
                <input type="text" class="form-control" id="gi">
            </div>
        </div>
        <div class="form-group">
            <label for="prodName">Подходит для:</label>
            <ul class="list-group">
                <li class="list-group-item list-group-item-success">
                    <div style="float: left;  margin-right: 37px;">Похудение</div>
                    <div class="custom-controls-stacked d-block my-3" style="float: left; margin: 0 !important;">
                        <label class="custom-control custom-radio">
                            <input id="radioStacked1" name="radio-ph" type="radio" class="custom-control-input" required>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Нет</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radioStacked2" name="radio-ph" type="radio" class="custom-control-input" required>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Да</span>
                        </label>
                    </div>
                </li>
                <li class="list-group-item list-group-item-warning">
                    <div style="float: left;  margin-right: 33px;">Поддрежка</div>
                    <div class="custom-controls-stacked d-block my-3" style="float: left; margin: 0 !important;">
                        <label class="custom-control custom-radio">
                            <input id="radioStacked1" name="radio-pd" type="radio" class="custom-control-input" required>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Нет</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radioStacked2" name="radio-pd" type="radio" class="custom-control-input" required>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Да</span>
                        </label>
                    </div>
                </li>
                <li class="list-group-item list-group-item-danger">
                    <div style="float: left;  margin-right: 20px;">Набор массы</div>
                    <div class="custom-controls-stacked d-block my-3" style="float: left; margin: 0 !important;">
                        <label class="custom-control custom-radio">
                            <input id="radioStacked1" name="radio-nb" type="radio" class="custom-control-input" required>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Нет</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radioStacked2" name="radio-nb" type="radio" class="custom-control-input" required>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Да</span>
                        </label>
                    </div>
                </li>
                <li class="list-group-item list-group-item-primary">
                    <div style="float: left;  margin-right: 38px;">Cheat meal</div>
                    <div class="custom-controls-stacked d-block my-3" style="float: left; margin: 0 !important;">
                        <label class="custom-control custom-radio">
                            <input id="radioStacked1" name="radio-cm" type="radio" class="custom-control-input" required>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Нет</span>
                        </label>
                        <label class="custom-control custom-radio">
                            <input id="radioStacked2" name="radio-cm" type="radio" class="custom-control-input" required>
                            <span class="custom-control-indicator"></span>
                            <span class="custom-control-description">Да</span>
                        </label>
                    </div>
                </li>
            </ul>
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
    </form>
@endsection