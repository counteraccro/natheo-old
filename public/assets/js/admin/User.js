/**
 *  JS gestion des users
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let User = {}

User.Launch = function() {

    User.globalId = '#admin-user-globale';

    /**
     * Charge la liste des users
     * @constructor
     */
    User.LoadListing = function () {
        let id = User.globalId + ' .card-body';
        let url = $(id).data('url');

        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    };
}