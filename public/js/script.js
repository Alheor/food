$( document ).ready(function() {

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

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

            if (
                el.id === 1 ||
                el.id === 3 ||
                el.id === 6 ||
                el.id === 8 ||
                el.id === 9 ||
                el.id === 14 ||
                el.id === 15 ||
                el.id === 16
            ){
                $('.cellulose').show();
            } else {
                $('.cellulose').hide();
            }

            return true;
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
            title: 'Торговая марка'
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

            return true;
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

        this.value = this.value.replace(/,/, '.');
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
    });

    $('.product-delete').on('click', function() {
        if (confirm('Уверены?')) {
            $(this).parent().parent().remove();
        }
    })
});

function productManufacturersSearch(input) {

    let mName = $('#ManufacturersSearch').val();

    let request = $.ajax({
        url: "/products_manufacturers",
        method: "GET",
        data: {
            search: mName
        }
    });

    request.fail(function(jqXHR) {
        modal.showError(jqXHR);
    });

    request.done(function(msg) {
        $('.manufacturers-list').html(msg);
    });
}

function productManufacturersAdd(obj) {
    let modal = new Modal({
        title: 'Новая торговая марка'
    });

    let request = $.ajax({
        url: "/addManufacturer",
        method: "GET"
    });

    modal.show();

    request.fail(function(jqXHR) {
        modal.showError(jqXHR);
    });

    request.done(function( msg ) {
        modal.html(msg);
    });

    modal.onAgree = function () {

        let mName = $('#manufacturerName').val();
        let mToken = $('#manufacturerToken').val();

        if(mName !== "undefined" && mName.length > 1) {
            let request = $.ajax({
                url: "/addManufacturer",
                method: "POST",
                data: {
                    mName: mName,
                    _token: mToken,
                }
            });

            request.fail(function (jqXHR) {
                modal.showError(jqXHR);
            });

            return true;
        } else {
            return false;
        };
    };
}