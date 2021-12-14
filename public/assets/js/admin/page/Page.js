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
         * Click pour ouvrir la modale de choix du template
         */
        $(Page.createUpdateGlobalId + ' #btn-modal-select-template').click(function() {

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
}