$(document).ready(function () {

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
});

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
            '            <td style="text-overflow: ellipsis;">' +
            '                <input type="hidden" value=\'' + JSON.stringify(msg) + '\' />' + msg.name +
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
    let resB =  Number(b) * Number(w) / 100;
    let resJ =  Number(j) * Number(w) / 100;
    let resU =  Number(u) * Number(w) / 100;

    if (isNaN(resB) || isNaN(resJ) || isNaN(resU) ) {
        alert('Ошибка калькуляции! Проблемы с расчетом БЖУ по весу');
        throw new Error('Calculate bju from weight problem!');
    }

    return {
        b: resB.toFixed(1),
        j: resJ.toFixed(1),
        u: resU.toFixed(1),
        k: Math.round(resB * 4 + resJ * 9 + resU * 4)
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
                // --- Белки ---

                // --- Жиры ---
                sumTJ += Number($($(el).find('td')[3]).text());
                // --- Жиры ---

                // --- Углеводы ---
                sumTU += Number($($(el).find('td')[4]).text());
                // --- Углеводы ---

                // --- Ккал ---
                sumTK += Number($($(el).find('td')[5]).text());
                // --- Ккал ---

            } else {
                $($(el).find('td')[1]).html(Math.round(sumTW));
                $($(el).find('td')[2]).html(sumTB.toFixed(1));
                $($(el).find('td')[3]).html(sumTJ.toFixed(1));
                $($(el).find('td')[4]).html(sumTU.toFixed(1));
                $($(el).find('td')[5]).html(Math.round(sumTK));
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