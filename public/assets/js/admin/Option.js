/**
 *  JS Options
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/

let Option = {};

Option.Launch = function () {

    Option.globalId = '#admin-options-globales';

    /**
     * Gestion des events sur les options
     * @constructor
     */
    Option.Event = function () {
        $(Option.globalId + ' .input-option').change(function () {

            let inputClass = 'is-invalid';
            let divClass = 'invalid-feedback';
            let input = $(this);
            let data = input.val();
            let url = $(Option.globalId).data('url');
            let loadingText = $(Option.globalId).data('loading');

            if (data == 'on') {
                if (input.is(':checked')) {
                    data = 1;
                } else {
                    data = 0;
                }
            }

            if(input.hasClass('require') && (data === "" || data === null))
            {
                Option.ShowReturnMessage(input, inputClass, divClass, input.data('msg-error'));
                return false;
            }

            input.parent().loader(loadingText);

            $.ajax({
                method: 'POST',
                url: url,
                data: {'key': input.attr('id'), 'value': data}
            })
                .done(function (json) {
                    input.parent().removeLoader();
                    if (json.success) {
                        inputClass = 'is-valid';
                        divClass = 'valid-feedback';
                    }
                    Option.ShowReturnMessage(input, inputClass, divClass, json.msg)
                });
        })

        $(Option.globalId + ' #GO_ADM_THEME_ADMIN').change(function() {
            let url = $('#cms-css-theme').attr('href');
            const theme = url.match(/theme\/(.*)/)[1];

            url = url.replace(theme, $(this).val()) + '.css';
            $('#cms-css-theme').attr('href', url);
        })
    }

    /**
     * Permet d'afficher un message sous une option
     * @param element
     * @param inputClass
     * @param divClass
     * @constructor
     */
    Option.ShowReturnMessage = function(element, inputClass, divClass, msg)
    {
        let timeout = 4000;

        element.addClass(inputClass);
        element.after('<div class="' + divClass + '">' + msg + '</div>');

        setTimeout(function(){
            element.removeClass(inputClass);
            element.next('.' + divClass).remove();
        }, timeout);
    }
}