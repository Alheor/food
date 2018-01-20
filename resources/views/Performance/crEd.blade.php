@extends('layout')
@section('content')
    @if($form == 'new_form')
        <div class="row">
            <div class="col-12 main-header-box">
                <h1>Физические показатели</h1>
            </div>
        </div>
            <form method="post" onsubmit="$('#submit_dutton')[0].disabled = true;">
                <div class="form-group">
                    <div class="col-12 main-body-box">
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
                            <label for="to_date">Дата</label>
                            <div class="input-group date">
                                <input type="text" placeholder="Сегодня" class="form-control" value="@if(isset($performance))@date($performance->to_date)@endif"@if(isset($performance))disabled=""@endif name="to_date" id="to_date">
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

                        <div class="row">
                            <div class="col-sm-2">
                                <label for="weight" class="col-form-label">Вес, кг <span class="text-danger font-weight-bold">*</span></label>
                                <input type="text" @if(old('weight')) value="{{ old('weight') }}" @elseif(isset($performance)) value="{{$performance->weight}}"@endif onkeyup="this.value = strToFloat(this.value);" autocomplete="off" class="form-control" id="weight" name="weight"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="general_musculature" class="col-form-label">Мышцы, кг.</label>
                                <input type="text" @if(old('general_musculature')) value="{{ old('general_musculature') }}" @elseif(isset($performance)) value="{{$performance->general_musculature}}"@endif onkeyup="this.value = strToFloat(this.value);" autocomplete="off" class="form-control" id="general_musculature" name="general_musculature"/>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-2">
                                <label for="general_fat" class="col-form-label">Жир, кг.</label>
                                <input type="text" @if(old('general_fat')) value="{{ old('general_fat') }}" @elseif(isset($performance)) value="{{$performance->general_fat}}"@endif onkeyup="this.value = strToFloat(this.value);" autocomplete="off" class="form-control" id="general_fat" name="general_fat"/>
                            </div>
                            <div class="col-sm-2">
                                <label for="general_fat_percent" class="col-form-label">Жир, %</label>
                                <input type="text" @if(old('general_fat_percent')) value="{{ old('general_fat_percent') }}" @elseif(isset($performance)) value="{{$performance->general_fat_percent}}"@endif onkeyup="this.value = strToFloat(this.value);" autocomplete="off" class="form-control" id="general_fat_percent" name="general_fat_percent"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="general_wather" class="col-form-label">Вода, л.</label>
                                <input type="text" @if(old('general_wather')) value="{{ old('general_wather') }}" @elseif(isset($performance)) value="{{$performance->general_wather}}"@endif onkeyup="this.value = strToFloat(this.value);" autocomplete="off" class="form-control" id="general_wather" name="general_wather"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-2">
                                <label for="metabolism" class="col-form-label">Метаболизм, Ккал.</label>
                                <input type="text" @if(old('metabolism')) value="{{ old('metabolism') }}" @elseif(isset($performance)) value="{{$performance->metabolism}}"@endif onkeyup="this.value = strToInt(this.value);" autocomplete="off" class="form-control" id="metabolism" name="metabolism"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row main-footer-box">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2">
                        <button type="submit" style="float: right;" id="submit_dutton" class="btn btn-success">Сохранить</button>
                    </div>
                </div>
            </form>

    @endif
@endsection