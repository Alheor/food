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
        let modal = new Modal({
            title: 'Категории продуктов'
        });

        let button = this;
        modal.onAgree = function () {
            let el = $('#category_tree_' + modal.guid).tree('getSelectedNode');
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

        let request = $.ajax({
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

        request.fail(function (jqXHR) {
            modal.spinner().error();
            modal.showError(jqXHR);
        });

        request.done(function (msg) {
            modal.html(msg);
        });
    });

    $('.calc_bju_field').on('keyup change', function () {
        let parent = $(this).parent().parent();

        this.value = this.value.replace(/,/, '.');
        let sum = 0;

        $(parent).find('input').each(function (i, el) {

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

    $('.product-delete').on('click', function () {
        if (confirm('Уверены?')) {
            $(this).parent().parent().remove();
        }
    });

    $('.product-add-div').on('click', function () {
        let dayNumber = $(this).find('input').first().val();

        let modal = new Modal({
            title: 'Добавить продукт или блюдо',
            successButtonLabel: 'Добавить',
            cancelButtonLabel: 'Закрыть'
        });


        modal.onAgree = function () {
            let selectEl = $('.manufacturers-list-selected');

            if (selectEl.length == 0 || selectEl.length > 1) {
                alert('Выберите продуки или блюдо из списка');
                return false;
            }

            let weight = $(selectEl).find('input')[1].value;

            if (weight == '' || Number(weight) < 1 || isNaN(Number(weight))) {
                alert('Введите вес');
                return false;
            }

            modal.spinner().show();
            return addDishProdToDiary(selectEl, modal, dayNumber, weight);
        };

        let request = $.ajax({
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
   return str.replace(/[^0-9]*/, '');
}

function strToFloat(str) {
    return str.replace(/[^0-9,.]*/, '').replace(/,/, '.');
}

function productManufacturersSearch() {

    let mName = $('#ManufacturersSearch').val();

    let request = $.ajax({
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
    let modal = new Modal({
        title: 'Новая торговая марка'
    });
    modal.spinner().show();

    let request = $.ajax({
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

        let mName = $('#manufacturerName').val();
        let mToken = $('#manufacturerToken').val();

        if (mName !== "undefined" && mName.length > 1) {
            let request = $.ajax({
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

function dishProdSearch() {

    let el = $('#dishProdSearch');

    let request = $.ajax({
        url: "/food_diary/finddp",
        method: "POST",
        data: {
            search: el.val(),
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

function addDishProdToDiary(el, modal, dayNumber, weight) {
    let id = $(el).find('td').first().find('input').val();
    let request = $.ajax({
        url: "/food_diary/finddp/" + id,
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
        let bjuk = calculateBJUFromWeight(weight, msg.b, msg.j, msg.u);

        let html = '<tr>\n' +
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
            '            <td style="padding-left: 10px;">\n' +
            '                <i class="fa fa-ban product-delete"  title="Удалить продукт или блюдо"  onclick="$(this).parent().parent().remove();calculateDiary();" aria-hidden="true"></i>\n' +
            '            </td>\n' +
            '        </tr>';

        $('#diaryTableAmount_' + dayNumber).before(html);

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
    let resB = Number(b) * Number(w) / 100;
    let resJ = Number(j) * Number(w) / 100;
    let resU = Number(u) * Number(w) / 100;

    if (isNaN(resB) || isNaN(resJ) || isNaN(resU) ) {
        alert('Ошибка калькуляции! Проблемы с расчетом БЖУ по весу');
        throw new Error('Calculate bju from weight problem!');
    }

    let tempResB = parseInt(resB * 10) / 10;
    let tempResJ = parseInt(resJ * 10) / 10;
    let tempResU = parseInt(resU * 10) / 10;

    return {
        b: tempResB,
        j: tempResJ,
        u: tempResU,
        k: parseInt(tempResB * 4 + tempResJ * 9 + tempResU * 4)
    }
}

function calculateDiary() {
    let diaryTable = $('.diaryTable');
    let sumW = 0;
    let sumB = 0;
    let sumJ = 0;
    let sumU = 0;
    let sumK = 0;

    $(diaryTable).each(function (z, el) {
        let sumTW = 0;
        let sumTB = 0;
        let sumTJ = 0;
        let sumTU = 0;
        let sumTK = 0;

        $($(el).find('tbody')).find('tr').each(function (i, el) {
            if (!$(el).hasClass('diaryTableAmount')) {
                let dPdata = JSON.parse($(el).find('td').first().find('input').val());

                // --- Вес ---
                let sumTWtmp = Number($($(el).find('td')[1]).find('input').val().replace(/[^0-9]*/g, ''));
                if (isNaN(sumTWtmp)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Вес" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Weight value problem! Table '+ z +', tr '+ i + ', td 1.');
                }

                let bjuk = calculateBJUFromWeight(sumTWtmp, dPdata.b, dPdata.j, dPdata.u);

                $($(el).find('td')[2]).text(bjuk.b);
                $($(el).find('td')[3]).text(bjuk.j);
                $($(el).find('td')[4]).text(bjuk.u);
                $($(el).find('td')[5]).text(bjuk.k);

                sumTW += sumTWtmp;
                // --- Вес ---

                // --- Белки ---
                sumTB += Number($($(el).find('td')[2]).text());
                if (isNaN(sumTWtmp)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Белки" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Protein value problem! Table '+ z +', tr '+ i + ', td 1.');
                }
                // --- Белки ---

                // --- Жиры ---
                sumTJ += Number($($(el).find('td')[3]).text());
                if (isNaN(sumTWtmp)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Жиры" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Fat value problem! Table '+ z +', tr '+ i + ', td 1.');
                }
                // --- Жиры ---

                // --- Углеводы ---
                sumTU += Number($($(el).find('td')[4]).text());
                if (isNaN(sumTWtmp)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Углеводы" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Carbohydrates value problem! Table '+ z +', tr '+ i + ', td 1.');
                }
                // --- Углеводы ---

                // --- Ккал ---
                sumTK += Number($($(el).find('td')[5]).text());
                if (isNaN(sumTWtmp)) {
                    alert('Ошибка калькуляции! Проблемы с полем "Ккал" таблицы '+ (z+1) +', строки '+ (i+1));
                    throw new Error('Kcal value problem! Table '+ z +', tr '+ i + ', td 1.');
                }
                // --- Ккал ---

            } else {
                $($(el).find('td')[1]).html(Math.round(sumTW));
                $($(el).find('td')[2]).html(sumTB.toFixed(1));
                $($(el).find('td')[3]).html(sumTJ.toFixed(1));
                $($(el).find('td')[4]).html(sumTU.toFixed(1));
                $($(el).find('td')[5]).html(Math.round(sumTK.toFixed(1)));
            }
        });

        sumW += sumTW;
        sumB += sumTB;
        sumJ += sumTJ;
        sumU += sumTU;
        sumK += sumTK;
    });

    let tr = $('.diaryTableResult').find('tr')[2];
    $($(tr).find('td')[0]).text(Math.round(sumW));
    $($(tr).find('td')[1]).text(sumB.toFixed(1));
    $($(tr).find('td')[2]).text(sumJ.toFixed(1));
    $($(tr).find('td')[3]).text(sumU.toFixed(1));
    $($(tr).find('td')[4]).text(Math.round(sumK));
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

    let diaryTable = $('.diaryTable');
    let _token = $(obj).parent().find('input').val();
    let data = [];
    let myWeight = $('#my_weight');
    let toDate = $('#to_date');
    let productExist = false;
    let product_guid = $('#product_guid');

    $(diaryTable).each(function (z, el) {
        data[z] = [];
        $($(el).find('tbody')).find('tr').each(function (i, el) {
            let elDataInput = $(el).find('input')[0];
            let elWeightInput = $(el).find('input')[1];

            if (typeof elDataInput !== "undefined" ) {
                if (typeof elWeightInput === "undefined" ) {
                    alert('Ошибка сохранения данных! Проблемы с весом таблицы '+ (z+1) +', строки '+ (i+1));
                    obj.disabled = false;
                    throw new Error('Weight value problem! Table '+ z +', tr '+ i + ', td 2.');
                }

                let elData = JSON.parse(elDataInput.value);
                data[z].push({
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

    let request = $.ajax({
        url: "/food_diary/save_day",
        method: "POST",
        data: {
            'data': {'products': data, 'weight': myWeight.val()},
            'guid' : product_guid.length === 0? null : product_guid.val(),
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

