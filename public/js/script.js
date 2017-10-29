$( document ).ready(function() {
    $('.category-list span').on('click', function () {
        $(this.parentNode).find('ul').first().toggle("slow");
    });

    $('#productCategorySelect').on('click', function () {
        let modal = new Modal({
            title: 'Категории продуктов'
        });

        var button = this;
        modal.onAgree = function () {
            let el = $('#category_tree_'+ modal.guid).tree('getSelectedNode');
            $(button).html(el.name);
            button.value = el.id;
        };

        let request = $.ajax({
            url: "/products_category",
            method: "GET",
            data: {
                guid: modal.guid
            }
        });

        modal.show();

        request.fail(function(jqXHR) {
            modal.showError(jqXHR);
        });

        request.done(function( msg ) {
            modal.html(msg);
        });
    });
});
