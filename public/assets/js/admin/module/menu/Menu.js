/**
 *  JS gestion des menu pour l'admin
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let Menu = {}

Menu.Launch = function () {

    Menu.globalId = '#admin-menu-globale';

    /**
     * Charge la liste des tags
     * @constructor
     */
    Menu.LoadListing = function () {
        let id = Menu.globalId + ' .card-body';
        let url = $(id).data('url');

        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    };

    /**
     * Event sur l'ajout / edition d'un role
     * @constructor
     */
    Menu.CreateUpdate = function () {

    }
}