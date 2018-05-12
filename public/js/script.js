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

        if (this.value.length > 0) {
            this.value = this.value.replace(/,/, '.');
        }

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
        strToFloat(this);
    });

    $('.to-int').on('keyup', function () {
        strToInt(this);
    });

    //Что бы при сабмите всех форм отображался прогрессбар
    $('form').each(function (i, el) {
        $(el).on('submit', function () {
            progress().start();
        });
    });
});

function strToInt(str) {
    if(str.value.length > 0) {
        return str.value = parseInt(str.value);
    }
}

function strToFloat(str) {
    if(str.value.length > 0) {
        return str.value = parseFloat(str.value);
    }
}

function escapeHtml(text) {
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };

    return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

function dishProdSearch() {
    debounce(function () {
        var parent = $('.modal-body');
        var searchType = parent.find('.search_type').data('content');
        var search = parent.find('.dishProdSearch').val();

        if (search.length > 1) {
            //parent.find('.modal-block').show();
            progress().endSuccess(false);
            progress().start();

            var request = $.ajax({
                url: "/food_diary/finddp",
                method: "POST",
                data: {
                    search: search,
                    type: searchType,
                    _token: $('#form_token').val()
                }
            });

            request.fail(function (jqXHR) {
                progress().endFail();
                //parent.find('.modal-block').hide();
            });

            request.done(function (responce) {
                progress().endSuccess(false);
                buildSearchPDList(responce);
                //parent.find('.modal-block').hide();
            });
        }
    }, 500);
}

function hashCode(string) {
    var hash = 0, i, chr;
    if (string.length === 0) return hash;
    for (i = 0; i < string.length; i++) {
        chr   = string.charCodeAt(i);
        hash  = ((hash << 5) - hash) + chr;
        hash |= 0; // Convert to 32bit integer
    }
    return hash;
}

var debounceTimer = [];
function debounce(func, time) {
    var funcKey = hashCode(String(func));

    if (typeof debounceTimer[funcKey] !== 'undefined') {
        clearTimeout(debounceTimer[funcKey]);
    }

    debounceTimer[funcKey] = setTimeout(function () {
        delete(debounceTimer[funcKey]);
        func();
    }, time);
}

function addDishProdToList(el) {
    var parent = $(el).closest('.dish-prod-el');
    var weight = parent.find('input[type=number]');
    var json = weight.data('content');
    var type = ($('.dishTableAmount').length > 0) ? 'dish' : 'diary';

    if (weight.val().length === 0 || Number(weight.val()) < 1 || isNaN(Number(weight.val()))) {
        alert('Введите вес');
        throw new Error('Weight is empty!');
    }

    var bjuk = calculateBJUFromWeight(weight.val(), json.b, json.j, json.u);

    var html = '<tr>\n' +
        '            <td style="text-overflow: ellipsis;">\n' +
        '                <input type="hidden" value=\'' + escapeHtml(JSON.stringify(json))+ '\' />\n' +
        '                <a  tabindex="0"  role="button" data-trigger="focus" class="dish-prod-info" data-toggle="dish-prod-info">' + json.name + '</a>' +
        '            </td>\n' +
        '            <td style="min-width: 38px;" class="weight-diary">\n' +
        '                <input type="number" value="' + weight.val() + '" class="form-control input-table dishProdWeight"/>\n' +
        '            </td>\n' +
        '            <td style="background-color: #c3e6cb; text-align: center;">' + bjuk.b + '</td>\n' +
        '            <td style="background-color: #ffeeba; text-align: center;">' + bjuk.j + '</td>\n' +
        '            <td style="background-color: #f5c6cb; text-align: center;">' + bjuk.u + '</td>\n' +
        '            <td style="text-align: center;">' + bjuk.k +
        '            </td>\n' +
        '            <td>\n' +
        '                <i class="fa fa-ban product-delete"  title="Удалить продукт или блюдо"  onclick="if(confirm(\'Удалить?\')){$(this).parent().parent().remove();' +
        (type === 'dish'? 'calculateDish()' : 'calculateDiary()') +
        '}" aria-hidden="true"></i>\n' +
        '            </td>\n' +
        '        </tr>';
    if (type === 'dish') {
        $('.dishTableAmount').before(html);
    } else {
        $('#diaryTableAmount_' + $('.modal-body').find('.meal-guid').data('guid')).before(html);
    }


    setTimeout(function () {
        $('[data-toggle="dish-prod-info"]').popover({
            trigger: 'focus',
            html: true,
            content: dishProdInfo(json)
        });
    }, 100);

    $('.dishProdWeight').unbind();

    $('.dishProdWeight').keyup(function (event) {
        this.value = this.value.replace(/[^0-9]*/g, '');
        setTimeout(function () {
            if (type === 'dish') {
                calculateDish();
            } else {
                calculateDiary();
            }
        }, 100);

    });

    setTimeout(function () {
        if (type === 'dish') {
            calculateDish();
        } else {
            calculateDiary();
        }
    }, 100);

    var button = parent.find('button');
    button.text('Успешно!');
    setTimeout(function () {
        button.parent().hide();
        button.text('Добавить >>');
        parent.find('.bju').show();
        weight.val('');
    }, 500);
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

function progress(progress) {
    var interval;
    var curWidth = 0;
    var windowWidth = $(window).width();

    if (typeof progress === 'undefined') {
        progress = $('.progress');
    }

    progress.width(0);
    progress.show();

    function start() {
        interval = setInterval(function () {
            curWidth = (windowWidth - progress.width()) / 200;
            progress.width(progress.width() + curWidth);
        }, 20);
    }

    function endSuccess(show_notification) {
        clearInterval(interval);
        progress.width(windowWidth);
        setTimeout(function () {
            progress.hide('fast');
        }, 500);

        if (typeof show_notification === 'undefined' || show_notification === true) {
            iziToast.success({
                title: ':)',
                message: 'Успешно!',
                position: 'topRight',
                timeout: 1000
            });
        }
    }

    function endFail(message) {
        clearInterval(interval);
        progress.width(windowWidth);
        setTimeout(function () {
            progress.hide('fast');
        }, 500);

        iziToast.error({
            title: ':(',
            message: typeof message === 'undefined'? 'Что-то сломалось, попробуйте позже' : message,
            position:'topRight',
            timeout: typeof message === 'undefined'? 3000 : 5000
        });
    }

    return {'start': start,'endSuccess': endSuccess,'endFail': endFail};
}

function buildSearchPDList(responce) {
    var dishProdList = $('.modal-body').find('.dish-prod-list');

    if (
        (responce.dish_list === 'undefined' || responce.dish_list.length === 0) &&
        (responce.product_list === 'undefined' || responce.product_list.length === 0)
    ) {
        dishProdList.html(
            '<tr style="font-size: 12px;"><td colspan="3">' + $('#dpa_empty_form').html() + '</td></tr>'
        );

        return null
    }

    var el = [];
    dishProdList.find('tr').remove();

    if (responce.product_list !== 'undefined' && responce.product_list.length > 0) {
        var productList = responce.product_list;
        for (var i in productList) {
            if (!productList.hasOwnProperty(i)) {
                continue;
            }

            el = productList[i];

            dishProdList.append(
                '<tr class="dish-prod-el" onclick="selectDishProdEl(this);">' +
                    '<td class="prod-search-name">' +
                        '<div>\n' +
                            '<span class="product" title="Продукт">П</span>\n' +
                             el.name +
                        '</div>' +
                        '<div>' + el.manufacturer.name + '</div>' +
                    '</td>' +
                    '<td class="weight">' +
                        '<input data-content="'+ escapeHtml(JSON.stringify(el)) +'" type="number" onkeyup="if(event.keyCode === 13){ addDishProdToList(this); } strToInt(this);" step="1" min="1" class="form-control input-table"/>' +
                    '</td>' +
                    '<td style="width: 132px; font-size: 14px; font-weight: normal;">\n' +
                        '<div class="bju">'+
                            '<div class="prod-search-el" style="background-color: #c3e6cb; font-size: 14px;">' + el.b + '</div>\n' +
                            '<div class="prod-search-el" style="background-color: #ffeeba; font-size: 14px;">' + el.j + '</div>\n' +
                            '<div class="prod-search-el" style="background-color: #f5c6cb; font-size: 14px;">' + el.u + '</div>\n' +
                            '<div class="prod-search-el" style="margin-right: 0 !important; width: 25px; font-size: 12px; margin-top: 1px;">' + parseInt(el.k) + '</div>\n' +
                        '</div>'+
                        '<div class="dish-prod-el-add" style="display: none;">' +
                            '<button class="btn btn-success" onclick="addDishProdToList(this);">Добавить >></button>'+
                        '</div>'+
                    '</td>' +
                '</tr>'
            );
        }
    }

    el = [];

    if (responce.dish_list !== 'undefined' && responce.dish_list.length > 0) {
        var dishList = responce.dish_list;
        for (var z in dishList) {
            if (!dishList.hasOwnProperty(z)) {
                continue;
            }

            el = dishList[z];

            dishProdList.append(
                '<tr class="dish-prod-el" onclick="selectDishProdEl(this);">' +
                    '<td class="prod-search-name">' +
                        '<div>\n' +
                            '<span class="dish" title="Блюдо">Б</span>\n' +
                            el.name +
                        '</div>' +
                    '</td>' +
                '<td class="weight">' +
                '<input data-content="'+ escapeHtml(JSON.stringify(el)) +'" type="number" onkeyup="if(event.keyCode === 13){ addDishProdToList(this); } strToInt(this);" step="1" min="1" class="form-control input-table"/>' +
                '</td>' +
                '<td style="width: 132px; font-size: 14px; font-weight: normal;">\n' +
                '<div class="bju">'+
                '<div class="prod-search-el" style="background-color: #c3e6cb; font-size: 14px;">' + el.b + '</div>\n' +
                '<div class="prod-search-el" style="background-color: #ffeeba; font-size: 14px;">' + el.j + '</div>\n' +
                '<div class="prod-search-el" style="background-color: #f5c6cb; font-size: 14px;">' + el.u + '</div>\n' +
                '<div class="prod-search-el" style="margin-right: 0 !important; width: 25px; font-size: 12px; margin-top: 1px;">' + parseInt(el.k) + '</div>\n' +
                '</div>'+
                '<div class="dish-prod-el-add" style="display: none;">' +
                '<button class="btn btn-success" onclick="addDishProdToList(this);">Добавить >></button>'+
                '</div>'+
                '</td>' +
                '</tr>'
            );
        }
    }

    el = [];

    if (responce.products_manufacturers !== 'undefined') {
        var mFilter =  $('.manufacturers-filter');
        var manufacturers = responce.products_manufacturers;

        mFilter.find('.manufacturer').remove();

        for (var x in manufacturers) {
            if (!manufacturers.hasOwnProperty(x)) {
                continue;
            }

            el = manufacturers[x];

            mFilter.append('<div onclick="manufacturerFilter(this)" class="badge badge-pill badge-light manufacturer">'+ el +'</div>');
        }
    }
}

function selectDishProdEl(obj) {
    var el = $(obj);
    var parent = el.parent();

    el.find('input[type=number]').focus();

    parent.find('tr').each(function (i, tr) {
        var tr = $(tr);

        if (tr[0] === el[0]) {
            tr.addClass('manufacturers-list-selected');
            tr.find('.bju').hide();
            tr.find('.dish-prod-el-add').show();
        } else {
            tr.removeClass('manufacturers-list-selected');
            tr.find('.bju').show();
            tr.find('.dish-prod-el-add').hide();
            tr.find('input[type=number]').val('');
        }
    });
}

function manufacturerFilter(obj) {
    var parent = $(obj).parent();

    if ($(obj).hasClass('all')) {
        parent.find('.manufacturer').removeClass('badge-success').addClass('badge-light');
        $(obj).removeClass('badge-light').addClass('badge-warning');
    } else {
        parent.find('.all').removeClass('badge-warning').addClass('badge-light');
        parent.find('.manufacturer').each(function (i, el) {
            if (el === obj) {
                $(el).removeClass('badge-light').addClass('badge-success');
            } else {
                $(el).removeClass('badge-success').addClass('badge-light');
            }
        });
    }

    if ($(obj).hasClass('all')) {
        $('.modal-body').find('.dish-prod-list>tr').show();
    } else {
        $('.modal-body').find('.dish-prod-list>tr').each(function (i, el) {
            var manufacturer = $(el).find('.prod-search-name>div:nth-child(2)');

            if(manufacturer.length > 0) {
                if (manufacturer.text() === $(obj).text()) {
                    manufacturer.closest('.dish-prod-el').show();
                } else {
                    manufacturer.closest('.dish-prod-el').hide();
                }
            } else {
                $(el).hide();
            }
        });
    }
}