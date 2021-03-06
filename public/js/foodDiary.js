$(document).ready(function () {
    $('.saveDiaryButton').on('click', function () {
        saveDiaryData(this);
    });
});

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
                $($(el).find('td')[2]).html(Math.ceil(sumTB));
                $($(el).find('td')[3]).html(Math.ceil(sumTJ));
                $($(el).find('td')[4]).html(Math.ceil(sumTU));
                $($(el).find('td')[5]).html(sumTK);
            }
        });

        sumW += sumTW;
        sumB += sumTB;
        sumJ += sumTJ;
        sumU += sumTU;
        sumK += sumTK;
    });

    var dtr = $('.diaryTableResult');
    var tr = dtr.find('tr')[2];

    if ($(tr).length > 0) {
        $($(tr).find('td')[0]).text(sumW);
        $($(tr).find('td')[1]).text(sumB.toFixed(1));
        $($(tr).find('td')[2]).text(sumJ.toFixed(1));
        $($(tr).find('td')[3]).text(sumU.toFixed(1));
        $($(tr).find('td')[4]).text(sumK);
    }

    tr = dtr.find('tr')[3];

    var sum = Math.ceil(parseFloat(sumB)) + Math.ceil(parseFloat(sumJ)) + Math.ceil(parseFloat(sumU));

    if ($(tr).length > 0) {
        if (sum > 0) {
            $($(tr).find('td')[1]).text((Math.ceil(parseFloat(sumB)) * 100 / sum).toFixed(1) + '%');
            $($(tr).find('td')[2]).text((Math.ceil(parseFloat(sumJ)) * 100 / sum).toFixed(1) + '%');
            $($(tr).find('td')[3]).text((Math.ceil(parseFloat(sumU)) * 100 / sum).toFixed(1) + '%');
        }
    }
}

function saveDiaryData(obj) {
    obj.disabled = true;

    var diaryTable = $('.diaryTable');
    var _token = $(obj).parent().find('input').val();
    var data = [];
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

    if (toDate.val().length === 0 && $('#day_new').length > 0) {
        alert('Укажите дату!');
        obj.disabled = false;
        throw new Error('Select date!');
    }

    if (!productExist) {
        alert('Добавьте хотя бы один продукт!');
        obj.disabled = false;
        throw new Error('Add product!');
    }

    progress().start();
    var request = $.ajax({
        url: "/food_diary/save_day",
        method: "POST",
        data: {
            'data': {'mealList': data},
            'guid' : day_guid.length === 0? null : day_guid.val(),
            'to_date' : toDate.val(),
            '_token':_token,
            'copy': $('#day_copy').length > 0,
            'new': $('#day_new').length > 0
        }
    });

    request.fail(function (jqXHR) {
        obj.disabled = false;
        progress().endFail();
    });

    request.done(function (msg) {
        if (typeof msg.status !== "undefined" && msg.status === 'success') {
            progress().endSuccess();
        } else {
            progress().endFail(msg.error_message);
        }

        obj.disabled = false;
    });
}