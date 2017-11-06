@if($tplName == 'button')
    <button type="button" name="manufacturer" class="btn btn-dark" id="productManufacturersSelect">
        @if(old('manufacturer_name')) {{old('manufacturer_name')}} @else Не выбрано @endif
    </button>
    <input type="hidden" name="manufacturer" value="{{ old('manufacturer') }}" id="productManufacturerId" />
    <input type="hidden" name="manufacturer_name" value="{{ old('manufacturer_name') }}" id="productManufacturerName" />
@elseif($tplName == 'productManufacturers')
    <table class="table table-striped table-bordered table-sm">
        <thead class="thead-dark">
        <tr>
            <th scope="col">#</th>
            <th scope="col">Наименование</th>
            <th scope="col">ИНН</th>
            <th scope="col">ОГРН</th>
        </tr>
        </thead>
        <tbody class="manufacturers-list">
        @foreach ($manufacturers as $manufacturer)
            <tr style="font-size: 12px;">
                <th scope="row">{{$manufacturer->id}}</th>
                <td>{{$manufacturer->name}}</td>
                <td>{{$manufacturer->inn}}</td>
                <td>{{$manufacturer->ogrn}}</td>
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
@endif