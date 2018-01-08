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

var timerProdSearch = false;
function dishProdSearch(obj, type) {
    function request() {
        var el = $('#dishProdSearch');

        if(el.val().length > 1) {
            $('#searchSendIndicator').html('<i class="fa fa-spinner fa-spin" style="font-size:24px;"></i>');

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
                $('#searchSendIndicator').html('<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true" style="font-size:24px;"></i>');
                console.log(jqXHR);
            });

            request.done(function (msg) {
                $('#searchSendIndicator').html('<i class="fa fa-check text-success" aria-hidden="true" style="font-size:24px;"></i>');
                setTimeout(function () {
                    //$('#searchSendIndicator').html('');
                }, 1000);
                $('.dish-prod-list').html(msg);
            });
        }
    }

    if(timerProdSearch !== false) {
        clearTimeout(timerProdSearch);
    }

    timerProdSearch = setTimeout(function () {
        request();
    }, 500);
}

function addDishProdToDiary(el, modal, dayGuid, weight) {
    var guid = $(el).find('td').first().find('input').val();
    var request = $.ajax({
        url: "/food_diary/finddp/" + guid,
        type: "all",
        method: "POST",
        data: {
            guid: guid,
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

function dishProdInfo(data) {
    return '<h6>' + data.name +
        '</h6><i>' + (typeof data.manufacturer === 'undefined'? '' : data.manufacturer.name) +
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