@if($tplName == 'button')
    <button type="button" name="manufacturer" class="btn btn-dark" id="productManufacturersSelect">
        @if(old('manufacturer_name')) {{old('manufacturer_name')}} @else Не выбрано @endif
    </button>
    <input type="hidden" name="manufacturer" value="{{ old('manufacturer') }}" id="productManufacturerId" />
    <input type="hidden" name="manufacturer_name" value="{{ old('manufacturer_name') }}" id="productManufacturerName" />
@elseif($tplName == 'productManufacturers')
    <nav class="navbar navbar-expand-xs navbar-light bg-light">
        <div class="navbar-collapse">
            <div class="pull-left">
                <a href="#" onclick="productManufacturersAdd(this);" class="btn btn-success">Новый</a>
            </div>
            <div class="pull-right">
                <i class="fa fa-refresh"
                   aria-hidden="true"
                   style="font-size: 20px; margin: 10px; margin-right: 0px; cursor: pointer;"
                   onclick="productManufacturersSearch(this);"
                ></i>
            </div>
            <div class="pull-right">
                <input class="form-control" id="ManufacturersSearch" onkeyup="productManufacturersSearch(this);" type="text" placeholder="Найти" aria-label="Найти">
            </div>
        </div>
    </nav>
    <table class="table table-striped table-bordered table-sm">
        <thead class="thead-dark">
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
        <input type="text"class="form-control col-sm-12" id="manufacturerName">
    </div>
    <input type="hidden" id="manufacturerToken" value="{{ csrf_token() }}" >
@endif