let Role = {}

Role.Launch = function() {

    Role.globalId = '#admin-role-globale';

    /**
     * Charge la liste des routes
     * @constructor
     */
    Role.LoadListing = function()
    {
        let id = Role.globalId + ' .card-body';
        let url = $(id).data('url');

        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    };
}