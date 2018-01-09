@if($oper == 'get_form')
    <nav class="navbar navbar-expand-xs navbar-light bg-light">
        <div class="navbar-collapse">
            <div class="pull-left" style="font-size: 13px;">
                <a href="{{ route('new_product') }}" target="_blank" class="text-info">Создать продукт</a>
                <br/>
                <a href="{{ route('new_dish', ['new']) }}" target="_blank" class="text-success">Создать блюдо</a>
            </div>
            <div class="col-4 pull-right" style="padding-right: 0;">
                {{ csrf_field() }}
                <input style="padding: 8px" class="form-control" id="dishProdSearch" onkeyup="dishProdSearch(this, '{{$type}}');" type="text" placeholder="Найти" aria-label="Найти">
            </div>
            <div class="pull-right" style="margin-top: 6px;">
                <div id="searchSendIndicator" style="float: right; margin-right: 5px;"></div>
            </div>
        </div>
    </nav>
    <table class="table table-striped table-bordered table-sm" style="margin-bottom: 0;">
        <thead class="thead-dark">
        <tr>
            <td scope="col" style="font-size: 14px; color: #777;">Наименование</td>
            <td style="width: 50px; text-align: center; font-size: 14px; color: #777;">Вес</td>
            <td style="width: 140px; font-size: 14px; color: #777;">
                <div class="prod-search-el">Б</div>
                <div class="prod-search-el">Ж</div>
                <div class="prod-search-el">У</div>
                <div class="prod-search-el">Ккал</div>
            </td>
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
        <td class="prod-search-name" style="padding-left: 2px;">
            <div>
                @if(!isset($product['weight_after']))
                    <span class="product" title="Продукт">П</span>
                @else
                    <span class="dish" title="Блюдо">Б</span>
                @endif
                {{$product['name']}}
            </div>
            @if(isset($product['manufacturer']))<div>{{$product['manufacturer']['name']}}</div>@endif
            <input type="hidden" value="{{$product['guid']}}" />
        </td>
        <td>
            <input type="text" class="form-control input-table" onkeyup="this.value = strToFloat(this.value);"/>
        </td>
        <td style="padding-top: 7px;">
            <div class="prod-search-el" style="background-color: #c3e6cb; font-size: 14px;">{{$product['b']}}</div>
            <div class="prod-search-el" style="background-color: #ffeeba; font-size: 14px;">{{$product['j']}}</div>
            <div class="prod-search-el" style="background-color: #f5c6cb; font-size: 14px;">{{$product['u']}}</div>
            <div class="prod-search-el" style="font-size: 14px;">{{$product['k']}}</div>
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