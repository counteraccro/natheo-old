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
        $(Translation.globalId + ' #btn-reset-translate , ' + Translation.globalId + ' #btn-reload-translate').click(function () {

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

        /**
         * Event au click sur le bouton pour filtrer les traductions
         */
        $(Translation.globalId + ' #btn-form-search-translation').click(function() {
            Translation.LoadListingTranslation();
        })
    };

    /**
     * Retourne un tableau de données du formulaire de recherche des traductions
     * @returns {*|jQuery}
     */
    Translation.getDataForm = function()
    {
        let data = $(Translation.globalId + ' #form-search-translation').serializeArray();

        let showError = true;
        data.forEach(function(element, i){
            const regex = /translation_/g;
            if(element.name.search(regex) === 0)
            {
                showError = false;
            }
        });

        if(showError)
        {
            $(Translation.globalId + ' #translation-error-langue').show();
        }
        else {
            $(Translation.globalId + ' #translation-error-langue').hide();
        }
        return data;
    }

    /**
     * Charge la liste des traductions
     * @constructor
     */
    Translation.LoadListingTranslation = function () {
        let id = Translation.globalId + ' #listing-translation';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        let data = Translation.getDataForm();

        $(id).loader(str_loading);
        $.ajax({
            method: 'POST',
            data : {'translation_filter' : data },
            url: url,
        })
            .done(function (html) {
               $(id).removeLoader();
               $(id).html(html);
            });

    }
}