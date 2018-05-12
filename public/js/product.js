$(document).ready(function () {
    $('#productCategorySelect').on('click', function () {

        var modal = new modalWindow();

        modal.constructor({
            title: 'Категории продуктов'
        });

        var button = this;
        modal.onAgree = function () {
            var el = $('#category_tree_' + modal.guid).tree('getSelectedNode');
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
                el.id === 16 ||
                el.id === 31
            ) {
                $('.cellulose').show();
            } else {
                $('.cellulose').hide();
            }

            return true;
        };

        modal.show();

        var request = $.ajax({
            url: "/products_category",
            method: "GET",
            data: {
                guid: modal.guid
            }
        });

        request.fail(function (jqXHR) {
            modal.spinner().error();
            modal.showError(jqXHR);
        });

        request.done(function (msg) {
            modal.html(msg);
        });
    });

    $('#productManufacturersSelect').on('click', function () {
        var modal = new modalWindow();

        modal.constructor({
            title: 'Торговая марка'
        });

        var button = this;
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

        var request = $.ajax({
            url: "/products_manufacturers",
            method: "GET",
            data: {
                guid: modal.guid
            }
        });

        modal.show();

        request.fail(function (jqXHR) {
            modal.spinner().error();
            modal.showError(jqXHR);
        });

        request.done(function (msg) {
            modal.html(msg);
        });
    });

    $('.product-delete').on('click', function () {
        if (confirm('Уверены?')) {
            $(this).parent().parent().remove();
        }
    });

    $('.product-add-div').on('click', function () {
        var dayGuid = $(this).find('input').first().val();
        var modal = new modalWindow();
        var dpa_form = $('#dpa_form');
        modal.constructor({
            title: 'Добавить продукт или блюдо',
            showCancelButton: false,
            showSuccessButton: false
        });

        modal.show();
        modal.html(dpa_form.html());

        var modalBody = $('.modal-body');
        modalBody.find('.manufacturer').remove();
        modalBody.find('.meal-guid').data('guid', dayGuid);
        modalBody.find('.dish-prod-list').html(
            '<tr style="font-size: 12px;"><td colspan="3">'+$('#dpa_start_form').html()+'</td></tr>'
        );

        setTimeout(function () {
            var modalBody = $('.modal-body');
            var dishProdSearch = modalBody.find('.dishProdSearch');
            dishProdSearch.focus();
            modalBody.find('.dpa-form-reset').on('click', function () {
                dishProdSearch.val('');
                dishProdSearch.focus();
            });
            modalBody.find('.select-search-type').find('.dropdown-item').on('click', function () {
                modalBody.find('.select-search-type>button').text($(this).text());
                dishProdSearch.focus();
            });
        }, 500);
    });
});

function productManufacturersSearch() {

    debounce(function () {
        var mName = $('#ManufacturersSearch').val();
        if(mName.length > 1) {
            progress().endSuccess(false);
            progress().start();
            var request = $.ajax({
                url: "/products_manufacturers",
                method: "GET",
                data: {
                    search: mName
                }
            });

            request.fail(function (jqXHR) {
                progress().endFail();
            });

            request.done(function (msg) {
                progress().endSuccess(false);
                $('.manufacturers-list').html(msg);
            });
        }
    }, 500);
}

function productManufacturersAdd() {
    var modal = new modalWindow();
    modal.constructor({
        title: 'Новая торговая марка'
    });

    modal.spinner().show();

    modal.show();

    var request = $.ajax({
        url: "/addManufacturer",
        method: "GET"
    });

    request.fail(function (jqXHR) {
        modal.spinner().error();
        modal.showError(jqXHR);
    });

    request.done(function (msg) {
        modal.spinner().success(false);
        modal.html(msg);
    });

    modal.onAgree = function () {

        var mName = $('#manufacturerName').val();
        var mToken = $('#manufacturerToken').val();

        if (mName !== "undefined" && mName.length > 1) {
            var request = $.ajax({
                url: "/addManufacturer",
                method: "POST",
                data: {
                    mName: mName,
                    _token: mToken,
                }
            });

            request.fail(function (jqXHR) {
                modal.spinner().error();
                modal.showError(jqXHR);
                return false;
            });

            return true;
        } else {
            return false;
        }
    };
}