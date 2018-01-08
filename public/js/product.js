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
                el.id === 16
            ) {
                $('.cellulose').show();
            } else {
                $('.cellulose').hide();
            }

            return true;
        };

        var request = $.ajax({
            url: "/products_category",
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
        modal.constructor({
            title: 'Добавить продукт или блюдо',
            successButtonLabel: 'Добавить',
            cancelButtonLabel: 'Закрыть'
        });

        modal.onAgree = function () {
            var selectEl = $('.manufacturers-list-selected');

            if (selectEl.length == 0 || selectEl.length > 1) {
                alert('Выберите продуки или блюдо из списка');
                return false;
            }

            var weight = $(selectEl).find('input')[1].value;

            if (weight == '' || Number(weight) < 1 || isNaN(Number(weight))) {
                alert('Введите вес');
                return false;
            }

            modal.spinner().show();
            return addDishProdToDiary(selectEl, modal, dayGuid, weight);
        };

        var request = $.ajax({
            url: "/food_diary/finddp",
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
});

function productManufacturersSearch() {

    var mName = $('#ManufacturersSearch').val();

    var request = $.ajax({
        url: "/products_manufacturers",
        method: "GET",
        data: {
            search: mName
        }
    });

    request.fail(function (jqXHR) {
        console.log(jqXHR);
    });

    request.done(function (msg) {
        $('.manufacturers-list').html(msg);
    });
}

function productManufacturersAdd() {
    var modal = new modalWindow();
    modal.constructor({
        title: 'Новая торговая марка'
    });

    modal.spinner().show();

    var request = $.ajax({
        url: "/addManufacturer",
        method: "GET"
    });

    modal.show();

    request.fail(function (jqXHR) {
        modal.spinner().error();
        modal.showError(jqXHR);
    });

    request.done(function (msg) {
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