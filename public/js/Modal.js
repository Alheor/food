class Modal {
    constructor(data) {
        this.title = data.title;
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
            '                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">\n' +
            '                        <span aria-hidden="true">&times;</span>\n' +
            '                    </button>\n' +
            '                </div>\n' +
            '                <div style="max-height: 300px; overflow: auto;" class="modal-body" id="modal_body_'+ this.guid +'">\n' +
            '                <i class="fa fa-spinner fa-spin" style="font-size:24px"></i>\n'+
            '                </div>\n' +
            '                <div class="modal-footer">\n' +
            '                    <button type="button" class="btn btn-primary" id="modal_agree_'+ this.guid +'">OK</button>\n' +
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
                modal.hide();
            }
        });
    }

    hide() {
        $('#'+this.guid).modal('toggle');
    }

    html(data) {
        $('#modal_body_' + this.guid).html(data);
    }

    showSpiner() {
        $('#modal_body_' + this.guid).html('<i class="fa fa-spinner fa-spin" style="font-size:24px"></i>');
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