@if($oper == 'search_form')
    @forelse ($productList as $product)
    <tr>
        <td class="prod-search-name" style="padding: 0px; padding-left: 2px;">
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
            <input type="text" class="form-control input-table" onkeyup="strToFloat(this);"/>
        </td>
        <td style="padding-top: 7px;">
            <div class="prod-search-el" style="background-color: #c3e6cb; font-size: 14px;">{{$product['b']}}</div>
            <div class="prod-search-el" style="background-color: #ffeeba; font-size: 14px;">{{$product['j']}}</div>
            <div class="prod-search-el" style="background-color: #f5c6cb; font-size: 14px;">{{$product['u']}}</div>
            <div class="prod-search-el" style="font-size: 14px; margin-right: 0px !important; width: 25px; padding-left: 4px;">{{(int)$product['k']}}</div>
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