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
        $(MediaLib.globalId + ' #tree-view-folder .caret').click(function () {

            let current = $(this);
            $(MediaLib.globalId + ' #tree-view-folder .caret').each(function() {
                $(this).removeClass('activeNode');
            })

            let element = current.next(".nested");
            element.toggleClass('active');
            current.toggleClass('caret-down');
            current.addClass('activeNode');

            if(!current.hasClass('caret-down'))
            {
                current.parent().find(".caret-down").removeClass('caret-down');
                current.parent().find(".active").removeClass('active');
                return false;
            }

            let id = MediaLib.globalId + ' #right-block-folder';
            let url = current.data('url');
            if(url === undefined)
            {
                MediaLib.loadContentFolder();
                return false;
            }

            let str_loading = $(id).data('loading');
            System.Ajax(url, id, true, str_loading);
        });
    }

    /**
     * Event sur le block médias
     * @constructor
     */
    MediaLib.EventBlockMedia = function() {

        /**
         * Permet de naviger dans les dossier depuis le fil d'arianne
         */
        $(MediaLib.globalId + ' #breadcrumb-folder-media .breadcrumb-item a').click(function() {

            let id = MediaLib.globalId + ' #right-block-folder';
            let url = $(this).attr('href');
            let str_loading = $(id).data('loading');
            System.Ajax(url, id, true, str_loading);

            id = $(this).data('id');
            $(MediaLib.globalId + ' #tree-view-folder .caret').each(function() {
                $(this).removeClass('activeNode');

                if(id === $(this).data('id'))
                {
                    $(this).parent().find(".caret-down").removeClass('caret-down');
                    $(this).parent().find(".active").removeClass('active');
                    $(this).addClass('activeNode');
                    $(this).addClass('caret-down');
                    let element = $(this).next(".nested");
                   element.addClass('active');
                }
            })

            return false;
        });

        /**
         * Event sur le choix du filtre de media
         */
        $(MediaLib.globalId + ' #btn-render-media input').click(function() {
            MediaLib.loadContentFolder();
        });

        /**
         * Event sur le choix d'affichage
         */
        $(MediaLib.globalId + ' #btn-render-render input').click(function() {
            MediaLib.loadContentFolder();
        });

        /**
         * Event sur le bouton de recherche
         */
        $(MediaLib.globalId + ' #btn-search-media').click(function () {
            MediaLib.loadContentFolder();
        });
    }

    /**
     * Permet de charger le block contenant les info du dossier courant
     */
    MediaLib.loadBlockFolder = function() {
        let id = MediaLib.globalId + ' #right-block-folder';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    }

    /**
     * Permet de charger les medias d'un dossier
     */
    MediaLib.loadContentFolder = function() {
        let id = MediaLib.globalId + ' #block-content-folder';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');

        let data = MediaLib.getDataFilterFolder();
        console.log(data);

        $(id).loader(str_loading);

        $.ajax({
            method: 'POST',
            data : {'media-filter' : data },
            url: url,
        })
            .done(function (html) {
                $(id).html(html);
            });
    }

    /**
     * Récupère les données de filtre pour l'affichage des médias d'un dossier
     */
    MediaLib.getDataFilterFolder = function() {

        let tab = {};
        $(MediaLib.globalId + ' #btn-render-media input').each(function () {
            if($(this).prop('checked'))
            {
                tab['media'] = $(this).attr('id').split('-')[2]
            }
        });

        let render = "";
        $(MediaLib.globalId + ' #btn-render-render input').each(function () {
            if($(this).prop('checked'))
            {
                tab['render'] = $(this).attr('id').split('-')[2]
            }
        });

        tab['search'] = $(MediaLib.globalId + ' #search-media').val();
        return tab;
    }
}