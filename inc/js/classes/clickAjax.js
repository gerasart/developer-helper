class clickAjax {
    constructor() {
        this.$ = jQuery;

        this.input = false;
        this.fields = false;
        this.result = false;
        this.setEvent();
    }

    setEvent() {
        this.$(document).on('click', '[data-ajax]', (e) => this.sendAjax(e));
    }

    sendAjax(e) {
        this.$but = this.$(e.target);
        this.action = this.$but.data('ajax');

        ['input', 'result', 'fields'].forEach(item => {
            let selector = this.$but.data(item);

            this[item] = this.$(`[data-id=${selector}]`);
        });



        let data = {
            action: this.action,
            fields: this.fields.find('input, select').serialize(),
            input: this.input.val(),
        };

        /* global ajaxurl */
        this.$.post(ajaxurl, data, (response) => {
            console.log(response);
            window.logger.log(JSON.stringify(response));
            if (this.result) {
                this.result.html(response);

                if (response.success) {
                    this.printResult(response.data);
                }
            }
        });
    }

    printResult(data) {
        this.result.html('');

        for (let key in data) {
            this.result.append(key + ": ");

            if ( typeof data[key] === 'string') {
                this.result.append(data[key]);
            } else {
                this.result.append(data[key].join(','));
            }


            this.result.append("\n");
        }
    }

}

new clickAjax();
