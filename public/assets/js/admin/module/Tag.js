/**
*  JS gestion des tag pour l'admin
*  @author Gourdon Aymeric
*  @version 1.0
**/
let Tag = {}

Tag.Launch = function () {

    Tag.globalId = '#admin-tag-globale';

    /**
     * Charge la liste des tags
     * @constructor
     */
    Tag.LoadListing = function () {
        let id = Tag.globalId + ' .card-body';
        let url = $(id).data('url');

        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    };

    /**
     * Event sur l'ajout / edition d'un role
     * @constructor
     */
    Tag.CreateUpdate = function() {

        Tag.createUpdateId = '#admin-create-update-tag';

        $(Tag.createUpdateId + ' #render-exemple-tag').css({"background-color" : $(Tag.createUpdateId + ' #tag_color').val()});
        $(Tag.createUpdateId + ' #render-exemple-tag').html($(Tag.createUpdateId + ' #tag_name').val());

        /**
         * Au changement du short label, on met à jour l'exemple
         */
        $(Tag.createUpdateId + ' #tag_name').keyup(function() {
            $(Tag.createUpdateId + ' #render-exemple-tag').html($(this).val());
        });

        /**
         * AU changement de la couleur on met à jour l'exemple
         */
        $(Tag.createUpdateId + ' #tag_color').change(function() {
            $(Tag.createUpdateId + ' #render-exemple-tag').css({"background-color" : $(this).val()});
        });
    }
}