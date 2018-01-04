@if($oper == 'get_form')
    <nav class="navbar navbar-expand-xs navbar-light bg-light">
        <div class="navbar-collapse">
            <div class="pull-left" style="font-size: 13px;">
                <a href="{{ route('new_product') }}" target="_blank" class="text-info">Новый продукт</a>
                <br/>
                <a href="{{ route('new_dish') }}" target="_blank" class="text-success">Новое блюдо</a>
            </div>
            <div class="pull-right">
                <i class="fa fa-refresh"
                   aria-hidden="true"
                   style="font-size: 20px; margin:5px; margin-top: 10px; margin-right: 0px; cursor: pointer;"
                   onclick="dishProdSearch(this);"
                ></i>
            </div>
            <div class="col-6 pull-right">
                {{ csrf_field() }}
                <input class="form-control" id="dishProdSearch" onkeyup="dishProdSearch(this);" type="text" placeholder="Найти" aria-label="Найти">
            </div>
        </div>
    </nav>
    <table class="table table-striped table-bordered table-sm">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Наименование</th>
            <th style="width: 50px; text-align: center;">Вес</th>
            <th style="width: 160px;">
                <div class="prod-search-el">Б</div>
                <div class="prod-search-el">Ж</div>
                <div class="prod-search-el">У</div>
                <div class="prod-search-el">Ккал</div>
            </th>
        </tr>
        </thead>
        <tbody class="dish-prod-list">
            <tr style="font-size: 12px;">
                <td colspan="3">Введите слово для поиска...</td>
            </tr>
        </tbody>
    </table>
@endif

@if($oper == 'search_form')
    @forelse ($productList as $product)
    <tr>
        <td class="prod-search-name">
            <div>{{$product->name}}</div>
            <div>{{$product->manufacturer->name}}</div>
            <input type="hidden" value="{{$product->id}}" />
        </td>
        <td>
            <input type="text" class="form-control input-table" onkeyup="this.value = strToFloat(this.value);"/>
        </td>
        <td style="padding-top: 7px;">
            <div class="prod-search-el" style="background-color: #c3e6cb">{{$product->b}}</div>
            <div class="prod-search-el" style="background-color: #ffeeba">{{$product->j}}</div>
            <div class="prod-search-el" style="background-color: #f5c6cb">{{$product->u}}</div>
            <div class="prod-search-el">{{$product->k}}</div>
        </td>
    </tr>
    @empty
        <tr style="font-size: 12px;">
            <td colspan="3">Ничего не найдено...</td>
        </tr>
    @endforelse
    <script type="application/javascript">
        $('.dish-prod-list').find('tr').on('click', function () {
            $('.dish-prod-list').find('tr').removeClass('manufacturers-list-selected');
            $(this).addClass('manufacturers-list-selected');

            $('.dish-prod-list').find('tr').each(function () {
                $(this).find('input')[1].value = '';
            });
            $(this).find('input').focus();
        });
    </script>
@endif