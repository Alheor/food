function modalWindow() {
    this.constructor = function(data) {
        this.title = data.title;
        this.successButtonLabel = typeof data.successButtonLabel === "undefined"? 'OK' : data.successButtonLabel;
        this.cancelButtonLabel = typeof data.cancelButtonLabel === "undefined"? 'Отмена' : data.cancelButtonLabel;
        this.showSuccessButton = typeof data.showSuccessButton === "undefined"? true : data.showSuccessButton;
        this.showCancelButton = typeof data.showCancelButton === "undefined"? true : data.showCancelButton;
        this.guid = this.guidGenerate();
        this.prepareModal();
        this.onAgree = function () {
            console.log('nothing to do...');
        };
    };

    this.prepareModal = function() {
        this.htmlBody = '<div class="modal fade" id="'+ this.guid +'" tabindex="-1" role="dialog" aria-hidden="true">\n' +
            '        <div class="modal-dialog" role="document">\n' +
            '            <div class="modal-content">\n' +
            '                <div class="modal-header">\n' +
            '                    <h5 class="modal-title">'+ this.title +'</h5>\n' +
            '                    <button type="button" class="close" aria-label="Close" data-dismiss="modal">\n' +
            '                        <span aria-hidden="true">&times;</span>\n' +
            '                    </button>\n' +
            '                </div>\n' +
            '                <div style="max-height: 300px; overflow: auto; min-height: 175px;" class="modal-body" id="modal_body_'+ this.guid +'">\n' +
            '                   <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>\n'+
            '                </div>\n' +
            '                <div class="modal-footer" style="display:'+ (this.showSuccessButton === false && this.showCancelButton === false? 'none' : 'block') +';">' +'' +
            '                    <button style="display:'+ (this.showCancelButton === false? 'none' : 'block') +';" type="button" class="btn btn-secondary" data-dismiss="modal">'+ this.cancelButtonLabel +'</button>\n' +
            '                    <button style="display:'+ (this.showSuccessButton === false? 'none' : 'block') +';" type="button" class="btn btn-primary" id="modal_agree_'+ this.guid +'">'+ this.successButtonLabel +'</button>\n' +
            '            </div>\n' +
            '        </div>\n' +
            '    </div>';
    }

    this.show = function() {
        if (!$('div').is('#modal_body_' + this.guid)) {
            $(document.body).append(this.htmlBody);
        }
        $('#'+this.guid).modal('toggle');
        var modal = this;
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

    this.hide = function(flag) {
        var guid = this.guid;
        if(typeof flag == "undefined") {
            $('#' + guid).remove();

        } else {
            $('#'+guid).modal('hide');

        }
    }

    this.html = function(data, func) {
        $('#modal_body_' + this.guid).html(data);
        if(typeof func !== "undefined") {
            func();
        }
    }

    this.spinner = function() {

        return {
            success: function (message) {
                // $('#modal_speener_' + guid).html('<i class="fa fa-check text-success" aria-hidden="true" style="font-size:24px;"></i>');
                // setTimeout(function () {
                //     $('#modal_speener_' + guid).html('');
                // }, 1000);

                progress().endSuccess(message);
            },
            show:function () {
                //$('#modal_speener_' + guid).html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
                progress().start();
            },
            error:function () {
                // $('#modal_speener_' + guid).html('<i class="fa fa-exclamation-triangle text-danger" aria-hidden="true" style="font-size:24px;"></i>');
                progress().endFail(message);
            },
            hide:function () {
                //$('#modal_speener_' + guid).html('');
            }
        }
    }

    this.showError = function(data) {
        $('#modal_body_' + this.guid).html('<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Ошибка '+data.status+'!');
    }

    this.guidGenerate = function() {
        function s4() {
            return Math.floor((1 + Math.random()) * 0x10000)
                .toString(16)
                .substring(1);
        }
        return (s4() + s4() + '-' + s4() + '-' + s4() + '-' +
            s4() + '-' + s4() + s4() + s4()).toUpperCase();
    }

    return this;
}