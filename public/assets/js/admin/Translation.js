/**
 *  JS admin translation
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/


Translation = {};

Translation.Launch = function() {

    Translation.globalId = '#admin-translation-globale';

    /**
     * Charge la liste des traductions
     * @constructor
     */
    Translation.LoadListingTranslation = function() {
        let id = Translation.globalId + ' #listing-translation';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    }
}