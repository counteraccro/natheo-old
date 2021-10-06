/**
 *  JS global system
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let System = {};

System.Ajax = function (url, id_done, loader = true, str_loader = "") {
    if (loader) {
        $(id_done).loader(str_loader);
    }

    $.ajax({
        method: 'GET',
        url: url,
    })
        .done(function (html) {
            $(id_done).html(html);
        });
};

System.Paginate = function() {

    $('.pagination .page-item .page-link').click(function() {
        let url = $(this).attr('href');
        let str_loader = $('.pagination').data('loading');
        let id = $('.pagination').data('id');
        System.Ajax(url, id, true, str_loader);

        return false;
    });
};

/**
 * Gestion de l'input qui apparait au click sur un text
 * @constructor
 */
System.EventHiddenInput = function(scriptToExectAfter = null)
{
    $('.span-input-hidden .txt-input-switch').click(function() {

        $('.span-input-hidden .txt-input-switch').each(function() {
            let id = $(this).attr('id');
            $(this).show();
            $('#input-' + id).hide();
        })

        let id = $(this).attr('id');
        $(this).hide();
        $('#input-' + id).show();
    });

    $('.span-input-hidden .btn-reset-input-switch').click(function() {
        let id = $(this).data('id');

        $('#' + id).show();
        $('#input-' + id).hide();
    });

    $('.span-input-hidden .btn-submit-input-switch').click(function(e) {

        e.stopPropagation();

        let id = $(this).data('id');
        let url = $(this).data('url');
        let data = $('#input-' + id + ' textarea').val();
        $('#input-' + id + ' textarea').removeClass('is-invalid');

        if(data == '')
        {
            $('#input-' + id + ' textarea').addClass('is-invalid');
            return false;
        }

        $(this).parent().parent().parent().loader();

        $.ajax({
            method: 'POST',
            data : {'label' : data},
            url: url,
        })
            .done(function (html) {
                $('#input-' + id).hide();
                $('#' + id).html(data);
                $('#' + id).show();
                $('#success-' + id).show('slow').delay(1000).hide('slow');
                $(this).parent().parent().parent().removeLoader();

                if(scriptToExectAfter != null)
                {
                    eval(scriptToExectAfter);
                }
            });
    });


}