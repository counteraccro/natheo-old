/**
 *  JS admin translation
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/


Translation = {};

Translation.Launch = function () {

    Translation.globalId = '#admin-translation-globale';

    /**
     * Event sur la page d'index
     * @constructor
     */
    Translation.PageListingEvent = function () {

        /**
         * Bouton de réinitialisation des traductions
         */
        $(Translation.globalId + ' #btn-reset-translate').click(function () {

            let id = '#admin-translation-globale';
            let url = $(this).data('url');
            let str_loading = $(this).data('loading');
            let str_loading2 = $(this).data('loading2');

            $(id).loader(str_loading);
            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (html) {
                    $(id).removeLoader();
                    $(id).loader(str_loading2);
                    location.reload();
                });
        });
    };

    /**
     * Charge la liste des traductions
     * @constructor
     */
    Translation.LoadListingTranslation = function () {
        let id = Translation.globalId + ' #listing-translation';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    }
}