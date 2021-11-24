/**
 *  JS global pour les thème admin
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let Theme = {}

Theme.Launch = function() {

    Theme.globalId = '#admin-theme-globale';

    /**
     * Event sur la page index des themes
     * @constructor
     */
    Theme.Event = function()
    {
        $(Theme.globalId + " #btn-delete-theme").click(function() {
           Theme.loadModal($(this));
           return false;
        })
    }

    /**
     * Permet de charger le contenu d'une popin
     * @param element
     */
    Theme.loadModal = function(element) {

        let url = element.data('url');
        let str_loading = element.data('loading');
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
    }

    /**
     * Event sur la popin de supression d'un theme
     * @param modal
     * @constructor
     */
    Theme.EventDelete = function(modal) {
        Theme.globalIdDeleteTheme = '#modale-delete-theme';

        /**
         * Event sur le click du bouton confirmer
         */
        $(Theme.globalIdDeleteTheme + ' #btn-confirm-delete-theme').click(function() {

            let url = $(this).data('url');
            let str_loading = $(this).data('loading');
            let redirect = $(this).data('redirect');

            $(Theme.globalIdDeleteTheme + ' .modal-body').loader(str_loading);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (response) {
                    $(Theme.globalIdDeleteTheme + ' .modal-body').removeLoader();
                    $(Theme.globalIdDeleteTheme + ' .modal-body').html(response.msg);

                    setTimeout(function(){
                        modal.toggle();
                        document.location.href= redirect;
                    }, 1500);
                });
        })
    }
}