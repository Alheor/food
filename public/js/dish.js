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

        modal.show();

        var request = $.ajax({
            url: "/dish_category",
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

    $('.dish-add-div').on('click', function () {
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
        modalBody.find('.meal-guid').data('guid', dayGuid);
        modalBody.find('.manufacturer').remove();
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
                //modalBody.find('.dish-prod-list').find('tr').remove();
            });
            modalBody.find('.select-search-type').find('.dropdown-item').on('click', function () {
                modalBody.find('.select-search-type>button').text($(this).text());
                dishProdSearch.focus();
            });
        }, 500);
    });

    $('#dish_weight').on('keyup', function () {
        strToInt(this);
        calculateDish();
    });

    $('#create_dish').on('click', function () {
        saveDishData(this);
    });
});

function saveDishData(obj) {
    obj.disabled = true;

    var diaryTable = $('.diaryTable');
    var _token = $(obj).parent().find('input').val();
    var dishGuid = $('#dish_guid');
    var dishCopy = $('#dish_copy');
    var productList = [];
    var productExist = false;
    //var suitable_for = [];
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

    // $('#suitable_for').find('input').each(function (i, el) {
    //     if($(el).prop("checked")) {
    //         suitable_for.push(el.id);
    //     }
    // });
    //
    // if(suitable_for == '') {
    //     alert('Не указано для чего подходит это блюдо');
    //     obj.disabled = false;
    //     throw new Error('Suitable for not selected!');
    // }

    $('#resultSendIndicator').html('<i class="fa fa-spinner fa-spin" style="font-size:24px;"></i>');

    var weight_after = $('#dish_weight').val() !== ''? $('#dish_weight').val() : $('#products_weight').text();

    var request = $.ajax({
        url: "/dishes/" + (dishGuid.length > 0? dishGuid.val() : 'new') + '/' + (dishCopy.length > 0? 1 : 0),
        method: "POST",
        data: {
            'dish_name': prodName,
            'cat_id': dish_category,
            'draft': $('#draft').prop( "checked" ),
            'comment': $('#comment').val(),
            //'suitable_for': suitable_for,
            'data': {'product_list': productList, 'weight_after': weight_after},
            '_token':_token
        }
    });

    request.fail(function (jqXHR) {
        progress().endFail();
        obj.disabled = false;
    });

    request.done(function (msg) {
        if (typeof msg.status !== "undefined" && msg.status === 'success') {
            progress().endSuccess();
            $('#dish_guid_div').html('<input type="hidden" id="dish_guid" value="'+ msg.guid +'"/>');
        } else {
            progress().endFail(msg.error_message);
        }

        obj.disabled = false;
    });
}

function calculateDish() {
    var sumTW = 0;
    var sumTB = 0;
    var sumTJ = 0;
    var sumTU = 0;
    var sumTK = 0;

    $('.diaryTable').find('tbody').find('tr').each(function (i, el) {
        if (!$(el).hasClass('dishTableAmount') && !$(el).hasClass('dishTableAmountPer100')) {
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

            $($(el).find('td')[2]).text(bjuk.b);
            $($(el).find('td')[3]).text(bjuk.j);
            $($(el).find('td')[4]).text(bjuk.u);
            $($(el).find('td')[5]).text(bjuk.k);

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
        } else if ($(el).hasClass('dishTableAmountPer100')) {
            var weight = $('#dish_weight').val()
            var coeff = 1;
            if(weight !== '' && Number(weight) !== 0) {
                coeff = sumTW / Number(weight);
            }

            sumTW = sumTW === 0? 1: sumTW;
            var resB = sumTB * coeff * 100 / sumTW;
            var resJ = sumTJ * coeff * 100 / sumTW;
            var resU = sumTU * coeff * 100 / sumTW;
            var resK = sumTK * coeff * 100 / sumTW;

            $($(el).find('td')[2]).html(resB.toFixed(1));
            $($(el).find('td')[3]).html(resJ.toFixed(1));
            $($(el).find('td')[4]).html(resU.toFixed(1));
            $($(el).find('td')[5]).html((Math.ceil(resK)));

        } else if ($(el).hasClass('dishTableAmount')) {
            var weight = $('#dish_weight').val()
            var coeff = 1;
            if(weight !== '' && Number(weight) !== 0) {
                coeff = sumTW / Number(weight);
            }

            sumTW = sumTW === 0? 1: sumTW;

            $($(el).find('td')[1]).html(sumTW);
            $($(el).find('td')[2]).html(sumTB.toFixed(1));
            $($(el).find('td')[3]).html(sumTJ.toFixed(1));
            $($(el).find('td')[4]).html(sumTU.toFixed(1));
            $($(el).find('td')[5]).html((Math.ceil(sumTK)));
        }
    });
}