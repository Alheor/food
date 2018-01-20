@if($tplName == 'button')
    <button type="button" class="btn btn-dark" id="productCategorySelect">
        @if(old('category_name')) {{old('category_name')}} @else Не выбрано @endif
    </button>
    <input type="hidden" name="category" value="{{ old('category') }}" id="productCategoryId" />
    <input type="hidden" name="category_name" value="{{ old('category_name') }}" id="productCategoryName" />
@elseif($tplName == 'productCategories')
    <div id="category_tree_{{$jsguid}}" class="tree"></div>
    <script type="application/javascript">
          $('#category_tree_{{$jsguid}}').tree({
            data: {!! $categories !!},
            autoOpen: false,
            dragAndDrop: false,
            closedIcon: '&#9655;',
            openedIcon:'&#9661;'
        });
    </script>
@endif