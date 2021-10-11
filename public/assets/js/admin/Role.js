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

    /**
     * Event sur l'ajout / edition d'un role
     * @constructor
     */
    Role.CreateUpdate = function() {

        Role.createUpdateId = '#admin-create-update-role';
        $(Role.createUpdateId + ' #render-exemple-short-role').css({"background-color" : $(Role.createUpdateId + ' #role-color').val()});

        /**
         * Au changement du short label, on met à jour l'exemple
         */
        $(Role.createUpdateId + ' #role-short-label').keyup(function() {
            $(Role.createUpdateId + ' #render-exemple-short-role').html($(this).val());
        });

        /**
         * AU changement de la couleur on met à jour l'exemple
         */
        $(Role.createUpdateId + ' #role-color').change(function() {
            $(Role.createUpdateId + ' #render-exemple-short-role').css({"background-color" : $(this).val()});
        });


    }
}