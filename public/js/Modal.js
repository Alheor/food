class Modal {
    constructor(data) {
        this.title = data.title;
        this.successButtonLabel = typeof data.successButtonLabel === "undefined"? 'OK' : data.successButtonLabel;
        this.cancelButtonLabel = typeof data.cancelButtonLabel === "undefined"? 'Отмена' : data.cancelButtonLabel;
        this.guid = this.guidGenerate();
        this.prepareModal();
        this.onAgree = function () {
            console.log('nothing to do...');
        }
    }

    prepareModal() {
        this.htmlBody = '<div class="modal fade" id="'+ this.guid +'" tabindex="-1" role="dialog" aria-hidden="true">\n' +
            '        <div class="modal-dialog" role="document">\n' +
            '            <div class="modal-content">\n' +
            '                <div class="modal-header">\n' +
            '                    <h5 class="modal-title">'+ this.title +'</h5>\n' +
            '                    <button type="button" class="close" aria-label="Close" data-dismiss="modal">\n' +
            '                        <span aria-hidden="true">&times;</span>\n' +
            '                    </button>\n' +
            '                </div>\n' +
            '                <div style="max-height: 300px; overflow: auto;" class="modal-body" id="modal_body_'+ this.guid +'">\n' +
            '                <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>\n'+
            '                </div>\n' +
            '                <div class="modal-footer">' +
            '                    <div class="speener" id="modal_speener_'+ this.guid +'">' +
            '                    </div>\n' +
            '                    <button type="button" class="btn btn-primary" id="modal_agree_'+ this.guid +'">'+ this.successButtonLabel +'</button>\n' +
            '                    <button type="button" class="btn btn-secondary" data-dismiss="modal">'+ this.cancelButtonLabel +'</button>\n' +
            '                </div>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>';
    }

    show() {
        if (!$('div').is('#modal_body_' + this.guid)) {
            $(document.body).append(this.htmlBody);
        }
        $('#'+this.guid).modal('toggle');
        let modal = this;
        $('#modal_agree_' + this.guid).on('click', function () {
            if (true === modal.onAgree()) {
                modal.hide(true);
            }
        });

        $('#modal_close_' + this.guid).on('click', function () {
            modal.hide();
        });

        $('#'+this.guid).on("hidden.bs.modal", function () {
            modal.hide();
        });
    }

    hide(flag) {
        let guid = this.guid;
        if(typeof flag == "undefined") {
            $('#' + guid).remove();

        } else {
            $('#'+guid).modal('hide');

        }
    }

    html(data, func) {
        $('#modal_body_' + this.guid).html(data);
        if(typeof func !== "undefined") {
            func();
        }
    }

    spinner() {

        let guid = this.guid;
        return {
            success: function () {
                $('#modal_speener_' + guid).html('<i class="fa fa-check text-success" aria-hidden="true" style="font-size:24px;"></i>');
                setTimeout(function () {
                    $('#modal_speener_' + guid).html('');
                }, 1000);
            },
            show:function () {
                $('#modal_speener_' + guid).html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
            },
            error:function () {
                $('#modal_speener_' + guid).html('<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true" style="font-size:24px;"></i>');
            },
            hide:function () {
                $('#modal_speener_' + guid).html('');
            }
        }
    }

    showError(data) {
        $('#modal_body_' + this.guid).html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Ошибка '+data.status+'!');
    }

    guidGenerate() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return (s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4()).toUpperCase();
    }
}