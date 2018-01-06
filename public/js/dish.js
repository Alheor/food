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

    $('#dish_weight').on('keyup', function () {
        this.value = strToInt(this.value);
        calculateDishFromReadyMadeWidth(this.value);
    });

    $('#create_dish').on('click', function () {
        saveDishData(this);
    });
});

function saveDishData(obj) {
    obj.disabled = true;

    var diaryTable = $('.diaryTable');
    var _token = $(obj).parent().find('input').val();
    var productList = [];
    var productExist = false;
    var suitable_for = [];
    var prodName = $('#prodName').val();
    var dish_category = $('#dishCategoryId').val();

    $(diaryTable).find('tbody').find('tr').each(function (i, el) {
        var elDataInput = $(el).find('input')[0];
        var elWeightInput = $(el).find('input')[1];

        if (typeof elDataInput !== "undefined" ) {
            if (typeof elWeightInput === "undefined" ) {
                alert('Ошибка сохранения данных! Проблемы с весом, строка '+ (i+1));
                obj.disabled = false;
                throw new Error('Weight value problem! Tr '+ i + ', td 2.');
            }

            var elData = JSON.parse(elDataInput.value);
            productList.push({
                'guid': elData.guid,
                'weight': elWeightInput.value,
            });

            productExist = true;
        }
    });

    if (prodName == '') {
        alert('Наименование не заполнено!');
        obj.disabled = false;
        throw new Error('Field name required!');
    }

    if (dish_category == '') {
        alert('Категория не выбрана!');
        obj.disabled = false;
        throw new Error('Category not selected!');
    }

    if (!productExist) {
        alert('Добавьте хотя бы один продукт!');
        obj.disabled = false;
        throw new Error('Add product!');
    }

    $('#resultSendIndicator').html('<i class="fa fa-spinner fa-spin" style="font-size:24px;"></i>');

    $('#suitable_for').find('input').each(function (i, el) {
        if($(el).prop("checked")) {
            suitable_for.push(el.id);
        }
    });

    if(suitable_for == '') {
        alert('Не указано для чего подходит это блюдо');
        obj.disabled = false;
        throw new Error('Suitable for not selected!');
    }

    var request = $.ajax({
        url: "/dishes/new",
        method: "POST",
        data: {
            'prod_name': prodName,
            'cat_id': dish_category,
            'draft': $('#draft').prop( "checked" ),
            'comment': $('#comment').val(),
            'suitable_for': suitable_for,
            'data': {'product_list': productList, 'ready_made_width': $('#dish_weight').val()},
            '_token':_token
        }
    });

    request.fail(function (jqXHR) {
        $('#resultSendIndicator').html('<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true" style="font-size:24px;"></i>');
        obj.disabled = false;
    });

    request.done(function (msg) {
        if (typeof msg.status !== "undefined" && msg.status === 'success') {
            $('#resultSendIndicator').html('<i class="fa fa-check text-success" aria-hidden="true" style="font-size:24px;"></i>');
            setTimeout(function () {
                $('#resultSendIndicator').html('');
            }, 1000);
        } else {
            $('#resultSendIndicator').html('<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true" style="font-size:24px;"></i>');
        }

        obj.disabled = false;
    });
}

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
            '                <i class="fa fa-ban product-delete"  title="Удалить продукт"  onclick="if(confirm(\'Удалить?\')){$(this).parent().parent().remove();calculateDish();}" aria-hidden="true"></i>\n' +
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
                calculateDish();
            }, 100);

        });

        modal.spinner().success();
        setTimeout(function () {
            calculateDish();
        }, 100);
    });

    return false;
}

function calculateDishFromReadyMadeWidth(weight) {
    var sumTW = 0;
    var sumTB = 0;
    var sumTJ = 0;
    var sumTU = 0;
    var sumTK = 0;

    $('.diaryTable').find('tbody').find('tr').each(function (i, el) {
        if (!$(el).hasClass('diaryTableAmount')) {
            var dPdata = JSON.parse($(el).find('td').first().find('input').val());

            // --- Вес ---
            var sumTWtmp = Number($($(el).find('td')[1]).find('input').val().replace(/[^0-9]*/g, ''));
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Вес", строки '+ (i+1));
                throw new Error('Weight value problem! Tr '+ i + ', td 1.');
            }
            sumTW += sumTWtmp;
            // --- Вес ---

            var bjuk = calculateBJUFromWeight(sumTWtmp, dPdata.b, dPdata.j, dPdata.u);

            // --- Белки ---
            sumTB += bjuk.b;
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Белки", строки '+ (i+1));
                throw new Error('Protein value problem! Tr '+ i + ', td 1.');
            }
            // --- Белки ---

            // --- Жиры ---
            sumTJ += bjuk.j;
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Жиры", строки '+ (i+1));
                throw new Error('Fat value problem! Tr '+ i + ', td 1.');
            }
            // --- Жиры ---

            // --- Углеводы ---
            sumTU += bjuk.u;
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Углеводы", строки '+ (i+1));
                throw new Error('Carbohydrates value problem! Tr '+ i + ', td 1.');
            }
            // --- Углеводы ---

            // --- Ккал ---
            sumTK += bjuk.k;
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Ккал", строки '+ (i+1));
                throw new Error('Kcal value problem! Tr '+ i + ', td 1.');
            }
            // --- Ккал ---
        } else {
            var coeff = 1;
            if(weight !== '' && Number(weight) !== 0) {
                coeff = sumTW / Number(weight);
            }

            $($(el).find('td')[2]).html((sumTB * coeff).toFixed(1));
            $($(el).find('td')[3]).html((sumTJ * coeff).toFixed(1));
            $($(el).find('td')[4]).html((sumTU * coeff).toFixed(1));
            $($(el).find('td')[5]).html(Math.round((sumTK * coeff).toFixed(1)));
        }
    });
}

function calculateDish() {
    var sumTW = 0;
    var sumTB = 0;
    var sumTJ = 0;
    var sumTU = 0;
    var sumTK = 0;

     $('.diaryTable').find('tbody').find('tr').each(function (i, el) {
        if (!$(el).hasClass('diaryTableAmount')) {
            var dPdata = JSON.parse($(el).find('td').first().find('input').val());

            // --- Вес ---
            var sumTWtmp = Number($($(el).find('td')[1]).find('input').val().replace(/[^0-9]*/g, ''));
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Вес", строки '+ (i+1));
                throw new Error('Weight value problem! Tr '+ i + ', td 1.');
            }

            var bjuk = calculateBJUFromWeight(sumTWtmp, dPdata.b, dPdata.j, dPdata.u);

            $($(el).find('td')[2]).text(bjuk.b);
            $($(el).find('td')[3]).text(bjuk.j);
            $($(el).find('td')[4]).text(bjuk.u);
            $($(el).find('td')[5]).text(bjuk.k);

            sumTW += sumTWtmp;
            // --- Вес ---

            // --- Белки ---
            sumTB += Number($($(el).find('td')[2]).text());
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Белки", строки '+ (i+1));
                throw new Error('Protein value problem! Tr '+ i + ', td 1.');
            }
            // --- Белки ---

            // --- Жиры ---
            sumTJ += Number($($(el).find('td')[3]).text());
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Жиры", строки '+ (i+1));
                throw new Error('Fat value problem! Tr '+ i + ', td 1.');
            }
            // --- Жиры ---

            // --- Углеводы ---
            sumTU += Number($($(el).find('td')[4]).text());
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Углеводы", строки '+ (i+1));
                throw new Error('Carbohydrates value problem! Tr '+ i + ', td 1.');
            }
            // --- Углеводы ---

            // --- Ккал ---
            sumTK += Number($($(el).find('td')[5]).text());
            if (isNaN(sumTWtmp)) {
                alert('Ошибка калькуляции! Проблемы с полем "Ккал", строки '+ (i+1));
                throw new Error('Kcal value problem! Tr '+ i + ', td 1.');
            }
            // --- Ккал ---

        } else {
            $($(el).find('td')[1]).html(Math.round(sumTW));
            $($(el).find('td')[2]).html(sumTB.toFixed(1));
            $($(el).find('td')[3]).html(sumTJ.toFixed(1));
            $($(el).find('td')[4]).html(sumTU.toFixed(1));
            $($(el).find('td')[5]).html(Math.round(sumTK.toFixed(1)));

            $('#dish_weight').val(Math.round(sumTW));
        }
    });
}
