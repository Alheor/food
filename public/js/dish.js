$(document).ready(function () {
    $('#dishCategorySelect').on('click', function () {

        var modal = new modalWindow();

        modal.constructor({
            title: 'Категории блюд'
        });

        var button = this;
        modal.onAgree = function () {
            var el = $('#category_tree_' + modal.guid).tree('getSelectedNode');
            $(button).html(el.name);
            $('#dishCategoryId').attr('value', el.id);
            $('#dishCategoryName').attr('value', el.name);

            return true;
        };

        var request = $.ajax({
            url: "/dish_category",
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

    $('.dish-add-div').on('click', function () {

        var dayGuid = $(this).find('input').first().val();
        var modal = new modalWindow();
        modal.constructor({
            title: 'Добавить продукт',
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
            return addProductToDish(selectEl, modal, dayGuid, weight);
        };

        var request = $.ajax({
            url: "/food_diary/finddp",
            method: "GET",
            data: {
                type: 'product',
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

function addProductToDish(el, modal, dayGuid, weight) {
    var id = $(el).find('td').first().find('input').val();
    var request = $.ajax({
        url: "/food_diary/finddp/" + id,
        type: "product",
        method: "POST",
        data: {
            id: id,
            _token: el.parent().parent().parent().parent().find('input').first().val()
        }
    });

    request.fail(function (jqXHR) {
        modal.spinner().error();
        modal.showError(jqXHR);
    });

    request.done(function (msg) {
        var bjuk = calculateBJUFromWeight(weight, msg.b, msg.j, msg.u);

        var html = '<tr>\n' +
            '            <td style="text-overflow: ellipsis;">\n' +
            '                <input type="hidden" value=\'' + JSON.stringify(msg) + '\' />\n' +
            '                <a  tabindex="0"  role="button" data-trigger="focus" class="dish-prod-info" data-toggle="dish-prod-info">' + msg.name + '</a>' +
            '            </td>\n' +
            '            <td style="min-width: 40px;">\n' +
            '                <input type="integer" value="' + weight + '" class="form-control input-table dishProdWeight"/>\n' +
            '            </td>\n' +
            '            <td style="background-color: #c3e6cb; text-align: center;">' + bjuk.b + '</td>\n' +
            '            <td style="background-color: #ffeeba; text-align: center;">' + bjuk.j + '</td>\n' +
            '            <td style="background-color: #f5c6cb; text-align: center;">' + bjuk.u + '</td>\n' +
            '            <td style="text-align: center;">' + bjuk.k +
            '            </td>\n' +
            '            <td style="padding-left: 5px;">\n' +
            '                <i class="fa fa-ban product-delete"  title="Удалить продукт или блюдо"  onclick="if(confirm(\'Удалить?\')){$(this).parent().parent().remove();calculateDiary();}" aria-hidden="true"></i>\n' +
            '            </td>\n' +
            '        </tr>';

        $('#dishTableAmount').before(html);

        setTimeout(function () {
            $('[data-toggle="dish-prod-info"]').popover({
                trigger: 'focus',
                html: true,
                content: dishProdInfo(msg)
            });
        }, 100);

        $('.dishProdWeight').unbind();

        $('.dishProdWeight').keyup(function (event) {
            this.value = this.value.replace(/[^0-9]*/g, '');
            setTimeout(function () {
                calculateDiary();
            }, 100);

        });

        modal.spinner().success();
        setTimeout(function () {
            calculateDiary();
        }, 100);
    });

    return false;
}