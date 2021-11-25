/**
 *  JS gestion des faq Category pour l'admin
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let FAQCategory = {}

FAQCategory.Launch = function () {

    FAQCategory.globalId = '#admin-faq-globale';

    /**
     * Charge la liste des tags
     * @constructor
     */
    FAQCategory.LoadListing = function () {
        let id = FAQCategory.globalId + ' .card-body';
        let url = $(id).data('url');

        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    };
}