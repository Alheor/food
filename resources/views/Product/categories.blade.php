@if($tplName == 'button')
    <button type="button" class="btn btn-dark" id="productCategorySelect">
        @if(old('category_name')) {{old('category_name')}} @else @if(isset($product)) {{$product->productCategory->name}} @else Не выбрано @endif @endif
    </button>
    <input type="hidden" name="category"
           @if(old('category')) value="{{ old('category') }}" @else @if(isset($product)) value="{{$product->productCategory->id}}" @endif @endif
           id="productCategoryId" />
    <input type="hidden" name="category_name"
           @if(old('category_name')) value="{{ old('category_name') }}" @else @if(isset($product)) value="{{$product->productCategory->name}}" @endif @endif
           id="productCategoryName" />
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