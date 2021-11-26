/**
 *  JS global system
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let System = {};

/**
 * Identifiant du block des modales pour l'admin
 * @type {string}
 */
System.adminBlockModalId = '#admin-block-modal';

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
/**
 * Event sur le champ de recherche générique
 * @param SearchId
 * @constructor
 */
System.EventSearch = function(SearchId) {

    /**
     * Event sur le bouton search
     */
    $(SearchId + " .btn-search").click(function() {
        let field = $(this).data('value');
        let value = $(SearchId + " #input-search").val();
        let divId = $(this).data('id');
        let url = $(divId).data('url');

        $(SearchId + ' #btn-reset-search').show();

        $(divId).loader($(divId).data('loading'))

        $.ajax({
            method: 'POST',
            data : {search_data : {'field' : field, 'value' : value}},
            url: url,
        })
            .done(function (html) {
                $(divId).removeLoader();
                $(divId).html(html);
            });
    })

    /**
     * Event sur le bouton reset
     */
    $(SearchId + ' #btn-reset-search').click(function() {

        let divId = $(SearchId + " .btn-search").data('id');
        $(SearchId + "  #input-search").val('');
        let url = $(divId).data('url');
        $(SearchId + ' #btn-reset-search').hide();
        $(SearchId + " .btn-search").data('value', 'all');
        $(SearchId + " .btn-search").html($(SearchId + " .btn-search").data('reset'));

        $(divId).loader($(divId).data('loading'))

        $.ajax({
            method: 'POST',
            data : {search_data : {'field' : "reset", 'value' : ""}},
            url: url,
        })
            .done(function (html) {
                $(divId).removeLoader();
                $(divId).html(html);
            });
    })

    /**
     * Event sur la selection du champ à rechercher
     */
    $(SearchId + " .dropdown-item").click(function() {
        let field = $(this).data('value');

        $(SearchId + " .btn-search").data('value', field);
        if(field == "all")
        {
            $(SearchId + " .btn-search").html($(SearchId + " .btn-search").data('reset'));
        }
        else {
            $(SearchId + " .btn-search").html($(SearchId + " .btn-search").data('text') + " " + field);
        }

    })
}

/**
 * Permet de généré un mot de passe fort
 * @returns {string}
 * @constructor
 */
System.GeneratePassword = function() {
    const alpha = 'abcdefghijklmnopqrstuvwxyz';
    const calpha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const num = '1234567890';
    const specials = ',.!@#$%^&*';
    const options = [alpha, alpha, alpha, specials, calpha, calpha, num, num, specials, alpha, calpha, num];
    let opt, choose;
    let pass = "";
    for ( let i = 0; i < 12; i++ ) {
        opt = Math.floor(Math.random() * options.length);
        choose = Math.floor(Math.random() * (options[opt].length));
        pass = pass + options[opt][choose];
        options.splice(opt, 1);
    }
    return pass;
}

/**
 * Permet de vérifier si dans un formulaire une donnée à été modifié sans sauvgarde
 * @constructor
 */
System.CheckBeforeLeave = function() {
    let change = false;

    let msg = $('#content-admin').data('msg-leave');

    $("input").change(function() {
        change = true;
    })

    $("a").not("form a").not("a.dropdown-toggle").click(function(e) {

        e.stopPropagation();

        if(change)
        {
            let r = window.confirm(msg);
            if(r === false)
            {
                return false;
            }
        }
    })
}

/**
 * Convertie une string en string propre pour le slug
 * @param str
 * @returns {string}
 */
System.stringToSlug = function(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim
    str = str.toLowerCase();

    // remove accents, swap ñ for n, etc
    var from = "àáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
    var to   = "aaaaaeeeeiiiioooouuuunc------";

    for (var i=0, l=from.length ; i<l ; i++) {
        str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
    }

    str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
        .replace(/\s+/g, '-') // collapse whitespace and replace by -
        .replace(/-+/g, '-'); // collapse dashes

    return str;
}