/**
 *  JS gestion des routes
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/

let Route = {}

Route.Launch = function() {

    Route.globalId = '#admin-route-globale';

    /**
     * Évènement sur la page listing des routes
     * @constructor
     */
    Route.PageListingEvent = function()
    {
        $(Route.globalId + ' #btn-update-route').click(function () {
            let url = $(this).data('url');
            let str_loading = $(this).data('loading');
            let id = Route.globalId + ' .card-body';
            //System.Ajax(url, id, true, str_loading);

            $(id).loader(str_loading);
            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (html) {
                    Route.LoadListingRoute();
                });

            return false;
        });
    };

    /**
     * Charge la liste des routes
     * @constructor
     */
    Route.LoadListingRoute = function()
    {
        let id = Route.globalId + ' .card-body';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    };

    /**
     * Evènement sur le tableau des routes
     * @constructor
     */
    Route.ListingEvent = function()
    {

    };

    /**
     * Mise à jour de l'entete du tableau de route
     * @constructor
     */
    Route.UpdateInfoListing = function(nb_depreciate, nb_total, txtNb, txtDepreciate)
    {
        let id = Route.globalId + ' #info-route';
        $(id).html('<b>' + nb_total + '</b> ' + txtNb);
        if(nb_depreciate > 0)
        {
            $(id).append(' - <span class="text-danger"></i><i class="fas fa-exclamation-circle"></i><i>&nbsp;<b>' + nb_depreciate + '</b> ' + txtDepreciate + '</i></span>');
        }
    }
}