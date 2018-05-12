@if($tplName == 'button')
    <button type="button" name="manufacturer" class="btn btn-dark" id="productManufacturersSelect">
        @if(old('manufacturer_name')) {{old('manufacturer_name')}} @else @if(isset($product)) {{$product->manufacturer->name}} @else Не выбрано @endif @endif
    </button>
    <input type="hidden" name="manufacturer"
           @if(old('manufacturer')) value="{{ old('manufacturer') }}" @else @if(isset($product)) value="{{$product->manufacturer->id}}" @endif @endif
           id="productManufacturerId" />
    <input type="hidden" name="manufacturer_name"
           @if(old('manufacturer_name')) value="{{ old('manufacturer_name') }}" @else @if(isset($product)) value="{{$product->manufacturer->name}}" @endif @endif
           id="productManufacturerName" />
@elseif($tplName == 'productManufacturers')
    <div class="col-12 main-widget-box">
        <div class="row">
            <div class="col-12">
                <div class="pull-left">
                    <a href="#" onclick="productManufacturersAdd(this);" class="btn btn-success">Новая ТМ</a>
                </div>
                <div class="pull-right">
                    <button
                            class="btn btn-outline-info pull-right"
                            style="margin-left: 5px;"
                            type="submit"
                            title="Обновить"
                            onclick="productManufacturersSearch(this);">
                        <i class="fa fa-refresh"
                           aria-hidden="true"
                           onclick="productManufacturersSearch(this);"
                        ></i>
                    </button>
                    <input class="form-control"
                           value=""
                           name="search"
                           id="ManufacturersSearch"
                           style="width: 150px;"
                           type="text"
                           placeholder="Найти"
                           aria-label="Поиск"
                           onkeyup="productManufacturersSearch(this);" >
                </div>
            </div>
        </div>
    </div>
    <table class="table table-striped table-bordered table-sm">
        <thead class="thead-inverse">
        <tr>
            <th scope="col" style="width: 35px; text-align: center;">#</th>
            <th scope="col">Наименование</th>
        </tr>
        </thead>
        <tbody class="manufacturers-list">
        @foreach ($manufacturers as $manufacturer)
            <tr style="font-size: 12px;">
                <th scope="row" style="text-align: center;">{{$manufacturer->id}}</th>
                <td>{{$manufacturer->name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script type="application/javascript">
        $('.manufacturers-list').find('tr').on('click', function () {
            $('.manufacturers-list').find('tr').removeClass('manufacturers-list-selected');
            $(this).addClass('manufacturers-list-selected');
        });
    </script>
@elseif($tplName == 'productManufacturersSearch')
    @foreach ($manufacturers as $manufacturer)
        <tr style="font-size: 12px;">
            <th scope="row" style="text-align: center;">{{$manufacturer->id}}</th>
            <td>{{$manufacturer->name}}</td>
        </tr>
    @endforeach
    <script type="application/javascript">
        $('.manufacturers-list').find('tr').on('click', function () {
            $('.manufacturers-list').find('tr').removeClass('manufacturers-list-selected');
            $(this).addClass('manufacturers-list-selected');
        });
    </script>
@elseif($tplName == 'addManufacturer')
    <div class="form-group">
        <label for="manufacturerName">Наименование <span class="text-danger font-weight-bold">*</span></label>
        <input type="text"class="form-control" id="manufacturerName">
    </div>
    <input type="hidden" id="manufacturerToken" value="{{ csrf_token() }}" >
@endif