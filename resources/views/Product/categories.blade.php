@if($tplName == 'button')
    <button type="button" class="btn btn-dark" id="productCategorySelect">
        Не выбрано
    </button>
@elseif($tplName == 'productCategories')
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