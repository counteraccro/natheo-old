/**
 *  JS gestion des medias
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let MediaLib = {};

MediaLib.Launch = function () {

    MediaLib.globalId = '#admin-media-globale';

    /**
     * Permet de charger la vue tree folder pour les média
     */
    MediaLib.loadTreeFolder = function() {

        let id = MediaLib.globalId + ' #block-tree-view';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    }

    /**
     * Event sur le tree de dossier
     * @constructor
     */
    MediaLib.EventTreeFolder = function() {

        /**
         * Click sur un dossier
         */
        $('#tree-view-folder .caret').click(function () {
            let element = $(this).next(".nested");
            element.toggleClass('active');
            $(this).toggleClass('caret-down');
        });

    }

    MediaLib.loadContentFolder = function() {
        let id = MediaLib.globalId + ' #block-content-folder';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    }
}