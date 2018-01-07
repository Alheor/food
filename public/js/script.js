$(document).ready(function () {
    $(function () {
        $('[data-toggle="popover"]').popover()
    });

    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    $('.category-list span').on('click', function () {
        $(this.parentNode).find('ul').first().toggle("slow");
    });

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

    $('.calc_bju_field').on('keyup change', function () {
        var parent = $(this).parent().parent();

        this.value = this.value.replace(/,/, '.');
        var sum = 0;

        $(parent).find('input').each(function (i, el) {

            var cur_sum = el.value.replace(/[^0-9.,]*/g, '').replace(/,/, '.');

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

    $('.saveDiaryButton').on('click', function () {
        saveDiaryData(this);
    });

    $('.to-float').on('keyup', function () {
        this.value = strToFloat(this.value);
    });

    $('.to-int').on('keyup', function () {
        this.value = strToInt(this.value);
    });
});

function strToInt(str) {
   return str.replace(/[^0-9]*/g, '');
}

function strToFloat(str) {
    return str.replace(/[^0-9,.]*/g, '').replace(/,/g, '.');
}

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

function dishProdSearch(obj, type) {

    var el = $('#dishProdSearch');

    if(el.val().length > 1) {
        var request = $.ajax({
            url: "/food_diary/finddp",
            method: "POST",
            data: {
                search: el.val(),
                type: type,
                _token: el.parent().find('input').first().val()
            }
        });

        request.fail(function (jqXHR) {
            console.log(jqXHR);
        });

        request.done(function (msg) {
            $('.dish-prod-list').html(msg);
        });
    }
}

function addDishProdToDiary(el, modal, dayGuid, weight) {
    var id = $(el).find('td').first().find('input').val();
    var request = $.ajax({
        url: "/food_diary/finddp/" + id,
        type: "all",
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

        $('#diaryTableAmount_' + dayGuid).before(html);

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

function calculateBJUFromWeight(w, b, j, u) {
    var resB = Number(b) * Number(w) / 100;
    var resJ = Number(j) * Number(w) / 100;
    var resU = Number(u) * Number(w) / 100;

    if (isNaN(resB) || isNaN(resJ) || isNaN(resU) ) {
        alert('Ошибка калькуляции! Проблемы с расчетом БЖУ по весу');
        throw new Error('Calculate bju from weight problem!');
    }

    var tempResB = parseFloat(resB.toFixed(1));
    var tempResJ = parseFloat(resJ.toFixed(1));
    var tempResU = parseFloat(resU.toFixed(1));

    return {
        b: tempResB,
        j: tempResJ,
        u: tempResU,
        k: parseInt(tempResB * 4 + tempResJ * 9 + tempResU * 4)
    }
}

function calculateDiary() {
    var diaryTable = $('.diaryTable');
    var sumW = 0;
    var sumB = 0;
    var sumJ = 0;
    var sumU = 0;
    var sumK = 0;

    $(diaryTable).each(function (z, el) {
        var sumTW = 0;
        var sumTB = 0;
        var sumTJ = 0;
        var sumTU = 0;
        var sumTK = 0;

        $($(el).find('tbody')).find('tr').each(function (i, el) {
            if (!$(el).hasClass('diaryTableAmount')) {
                var dPdata = JSON.parse($(el).find('td').first().find('input').val());

                // --- Вес ---
                var sumTWtmp = Number($($(el).find('td')[1]).find('input').val().replace(/[^0-9]*/g, ''));
                if (isNaN(sumTWtmp)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Вес" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Weight value problem! Table '+ z +', tr '+ i + ', td 1.');
                }

                var bjuk = calculateBJUFromWeight(sumTWtmp, dPdata.b, dPdata.j, dPdata.u);

                $($(el).find('td')[2]).text(bjuk.b);
                $($(el).find('td')[3]).text(bjuk.j);
                $($(el).find('td')[4]).text(bjuk.u);
                $($(el).find('td')[5]).text(bjuk.k);

                sumTW += sumTWtmp;
                // --- Вес ---

                // --- Белки ---
                sumTB += bjuk.b;
                if (isNaN(sumTB)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Белки" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Protein value problem! Table '+ z +', tr '+ i + ', td 1.');
                }
                // --- Белки ---

                // --- Жиры ---
                sumTJ += bjuk.j;
                if (isNaN(sumTJ)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Жиры" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Fat value problem! Table '+ z +', tr '+ i + ', td 1.');
                }
                // --- Жиры ---

                // --- Углеводы ---
                sumTU += bjuk.u;
                if (isNaN(sumTU)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Углеводы" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Carbohydrates value problem! Table '+ z +', tr '+ i + ', td 1.');
                }
                // --- Углеводы ---

                // --- Ккал ---
                sumTK += bjuk.k;
                if (isNaN(sumTK)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Ккал" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Kcal value problem! Table '+ z +', tr '+ i + ', td 1.');
                }
                // --- Ккал ---

            } else {
                $($(el).find('td')[1]).html(sumTW);
                $($(el).find('td')[2]).html(sumTB.toFixed(1));
                $($(el).find('td')[3]).html(sumTJ.toFixed(1));
                $($(el).find('td')[4]).html(sumTU.toFixed(1));
                $($(el).find('td')[5]).html(sumTK);
            }
        });

        sumW += sumTW;
        sumB += sumTB;
        sumJ += sumTJ;
        sumU += sumTU;
        sumK += sumTK;
    });

    var tr = $('.diaryTableResult').find('tr')[2];

    if($(tr).length > 0) {
        $($(tr).find('td')[0]).text(sumW);
        $($(tr).find('td')[1]).text(sumB.toFixed(1));
        $($(tr).find('td')[2]).text(sumJ.toFixed(1));
        $($(tr).find('td')[3]).text(sumU.toFixed(1));
        $($(tr).find('td')[4]).text(sumK);
    }
}

function dishProdInfo(data) {
    return '<h6>' + data.name +
        '</h6><i>' + data.manufacturer.name +
        '</i><br>Пищевая ценность на 100 гр.' +
        '<table class="table table-bordered table-sm diaryTableResult">' +
        '<tr>' +
        '<td>Белки</td>' +
        '<td>Жиры</td>' +
        '<td>Угл.</td>' +
        '<td>Ккал.</td>' +
        '</tr>' +
        '<tr>' +
        '<td style="background-color: #c3e6cb;">' + data.b +'</td>' +
        '<td style="background-color: #ffeeba;">' + data.j +'</td>' +
        '<td style="background-color: #f5c6cb;  ">' + data.u +'</td>' +
        '<td>' + data.k +'</td>' +
        '</tr>' +
        '</table>';
}

function saveDiaryData(obj) {
    obj.disabled = true;

    var diaryTable = $('.diaryTable');
    var _token = $(obj).parent().find('input').val();
    var data = [];
    var myWeight = $('#my_weight');
    var toDate = $('#to_date');
    var productExist = false;
    var day_guid = $('#day_guid');

    $(diaryTable).each(function (z, el) {
        var mealGuid = $(el).find('input').first().val();
        data[z] = {
            'mealGuid': mealGuid,
            'productList': []
        };
        $($(el).find('tbody')).find('tr').each(function (i, el) {
            var elDataInput = $(el).find('input')[0];
            var elWeightInput = $(el).find('input')[1];

            if (typeof elDataInput !== "undefined" ) {
                if (typeof elWeightInput === "undefined" ) {
                    alert('Ошибка сохранения данных! Проблемы с весом таблицы '+ (z+1) +', строки '+ (i+1));
                    obj.disabled = false;
                    throw new Error('Weight value problem! Table '+ z +', tr '+ i + ', td 2.');
                }

                var elData = JSON.parse(elDataInput.value);
                data[z].productList.push({
                    'guid': elData.guid,
                    'weight': elWeightInput.value,
                });

                productExist = true;
            }
        });
    });


    if (myWeight.val() == '' || Number(myWeight.val()) < 1 || isNaN(Number(myWeight.val()))) {
        alert('Введите свой вес');
        obj.disabled = false;
        myWeight.focus();
        throw new Error('Self weight value problem!');
    }

    if (!productExist) {
        alert('Добавьте хотя бы один продукт!');
        obj.disabled = false;
        throw new Error('Add product!');
    }


    $('#resultSendIndicator').html('<i class="fa fa-spinner fa-spin" style="font-size:24px;"></i>');

    var request = $.ajax({
        url: "/food_diary/save_day",
        method: "POST",
        data: {
            'data': {'mealList': data, 'weight': myWeight.val()},
            'guid' : day_guid.length === 0? null : day_guid.val(),
            'to_date' : toDate.val(),
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

