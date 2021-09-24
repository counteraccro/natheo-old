/**
 *  JS gestion des routes
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/

let Route = {}

Route.Launch = function() {

    Route.globalId = '#admin-route-globale';

    Route.PageListingEvent = function()
    {
        $(Route.globalId + ' #btn-update-route').click(function () {
            let url = $(this).data('url');
            let str_loading = $(this).data('loading');
            let id = Route.globalId + ' .card-body';
            System.Ajax(url, id, true, str_loading);

            return false;
        })

    }
}