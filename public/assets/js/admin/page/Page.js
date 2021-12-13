/**
 *  JS gestion pages
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/

let Page = {};

Page.Launch = function() {

    /**
     * Event sur la création / édition d'une page
     * @constructor
     */
    Page.EventCreateUpdate = function(globalId) {

        Page.createUpdateGlobalId = globalId;
        /**
         * Event sur le choix de la langue
         */
        $(Page.createUpdateGlobalId + ' #select-language').change(function() {
            let language = $(this).val();
            let url = $(Page.createUpdateGlobalId + ' #block-form-page').data('url');
            let tabTmp = url.split('/');

            if(tabTmp.length === 7)
            {
                url = url.substring(0, url.length - 2) + language;
            }
            else {
                url = url + '/' + language;
            }
            Page.LoadFormPage(url);
        })
    }

    /**
     * Permet de charger le block contenant le formulaire de la page
     * @constructor
     */
    Page.LoadFormPage = function(url = null, urlChoiceLanguage = null)
    {
        let id = '#block-form-page';
        if(url == null)
        {
            url = $(id).data('url');
        }
        let str_loading = $(id).data('loading');
        //System.Ajax(url, id, true, str_loading);

        $(id).loader(str_loading);
        $.ajax({
            method: 'GET',
            url: url,
        })
            .done(function (html) {
                $(id).removeLoader(str_loading);
                $(id).html(html);
                Page.LoadChoiceLanguage();
            });
    }

    /**
     * Charge la selection de langue
     * @constructor
     */
    Page.LoadChoiceLanguage = function()
    {
        let id = '#select-language-page';
        let url = $(id).data('url');

        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    }

    /**
     * Event sur le block form appelé en ajax dans la vue createUpdate'
     * @constructor
     */
    Page.EventAjaxCreateUpdate = function(globalId) {

        Page.ajaxCreateUpdateGlobalId = globalId;

        /**
         * Click pour ouvrir la modale de choix du template
         */
        $(Page.ajaxCreateUpdateGlobalId + ' #btn-modal-select-template').click(function() {

            let url = $(this).attr('href');
            let str_loading = $(this).data('loading');
            let id = System.adminBlockModalId;

            $('body').loader(str_loading);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (html) {
                    $('body').removeLoader(str_loading);
                    $(System.adminBlockModalId).html(html);
                });

            return false;
        });
    }

    /**
     * Permet de charger le template de base pour la page
     * @constructor
     */
    Page.LoadTemplate = function()
    {
        let id = '#content-template';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    }

    /**
     * Event sur la selection d'un template
     * @param globalId
     * @constructor
     */
    Page.EventSelectTemplate = function(globalId) {
        Page.selectTemplateGlobalId = globalId;
        $(Page.selectTemplateGlobalId + ' #txt-success').hide();

        /**
         * Event sur le choix d'un template
         */
        $(Page.selectTemplateGlobalId + ' input[type="radio"]').change(function() {

            let url = $(Page.selectTemplateGlobalId).data('url-select')
            let str_loading = $(Page.selectTemplateGlobalId).data('loading');
            let val = $(this).val();

            url = url + '/' + val;

            $(Page.selectTemplateGlobalId + ' .modal-dialog').loader(str_loading);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (html) {
                    $(Page.selectTemplateGlobalId + ' .modal-dialog').removeLoader();
                    $(Page.selectTemplateGlobalId + ' #txt-success').show();

                    setTimeout(() => {   $(Page.selectTemplateGlobalId + ' #txt-success').hide(); }, 2000);

                    Page.LoadTemplate();
                });

        })
    }

    /**
     * Event sur la partie content de la création d'une page
     * @param globalId
     * @constructor
     */
    Page.EventContent = function(globalId)
    {
        Page.eventContentGlobalId = globalId;
    }

    /**
     * Event sur les boutons de choix de la langue
     * @param globalId
     * @constructor
     */
    Page.EventChoiceLanguage = function(globalId)
    {
        Page.eventChoiceLanguageGlobalId = globalId;

        $(Page.eventChoiceLanguageGlobalId + ' .btn-select-language').each(function() {

            let language = $(this).data('language');

            $('#select-language option').each(function() {
                let val = $(this).val();

                if(val === language)
                {
                    $('#select-language').children('option[value="' + val + '"]').remove();
                }
            })

            if($('#select-language option').length === 1)
            {
                $('#select-language').append('<option value="#" selected="selected">' + $('#select-language').data('empty') + '</option>');
                $('#select-language').prop('disabled', 'disabled');
            }
        })

        $(Page.eventChoiceLanguageGlobalId + ' .btn').click(function() {

            let language = $(this).data('language');
            let url = $('#block-form-page').data('url');
            let tabTmp = url.split('/');

            if(tabTmp.length === 7)
            {
                url = url.substring(0, url.length - 2) + language;
            }
            else {
                url = url + '/' + language;
            }
            Page.LoadFormPage(url);
        })
    }
}