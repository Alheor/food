@if($tplName == 'button')
    <button type="button" class="btn btn-dark" id="dishCategorySelect">
        @if(isset($category_name)) {{$category_name}} @else Не выбрано @endif
    </button>
    <input type="hidden" name="category" value="@if(isset($category)){{$category}}@endif" id="dishCategoryId" />
    <input type="hidden" name="category_name" value="@if(isset($category_name)){{$category_name}}@endif" id="dishCategoryName" />
@elseif($tplName == 'dishCategories')
    <div id="category_tree_{{$jsguid}}"></div>
    <script type="application/javascript">
          $('#category_tree_{{$jsguid}}').tree({
            data: {!! $categories !!},
            autoOpen: false,
            dragAndDrop: false,
            closedIcon: '+',
            openedIcon:'-'
        });
    </script>
@endif