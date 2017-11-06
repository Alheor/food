$( document ).ready(function() {
    $('.category-list span').on('click', function () {
        $(this.parentNode).find('ul').first().toggle("slow");
    });

    $('#productCategorySelect').on('click', function () {
        let modal = new Modal({
            title: 'Категории продуктов'
        });

        let button = this;
        modal.onAgree = function () {
            let el = $('#category_tree_'+ modal.guid).tree('getSelectedNode');
            $(button).html(el.name);
            $('#productCategoryId').attr('value', el.id);
            $('#productCategoryName').attr('value', el.name);
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

    $('#productManufacturersSelect').on('click', function () {
        let modal = new Modal({
            title: 'Производитель продуктов'
        });

        let button = this;
        modal.onAgree = function () {
            $('.manufacturers-list').find('tr').each(function (i, tr) {
                if ($(tr).hasClass('manufacturers-list-selected')) {
                    $(button).html($(tr).find('td').html());
                    $('#productManufacturerId').attr('value', $(tr).find('th').html());
                    $('#productManufacturerName').attr('value', $(tr).find('td').html());
                }
            });
        };

        let request = $.ajax({
            url: "/products_manufacturers",
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
    
    $('.calc_bju_field').on('keyup change', function () {
        let parent = $(this).parent().parent();
        let sum = 0;

        $(parent).find('input').each(function(i, el) {

            let cur_sum = el.value.replace(/[^0-9.,]*/g, '').replace(/,/, '.');

            if (cur_sum == '') {
                cur_sum = 0;
            }

            if (i < 3) {
                switch (i) {
                    case 0:
                        sum += parseFloat(cur_sum) * 4;
                        break;
                    case 1:
                        sum += parseFloat(cur_sum) * 9;
                        break;
                    case 2:
                        sum += parseFloat(cur_sum) * 4;
                }
            }
        });

        $('.calc_bju_k_res').attr('value', sum.toFixed(1));
    })
});
