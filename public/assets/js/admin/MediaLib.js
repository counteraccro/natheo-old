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
    MediaLib.loadTreeFolder = function (folder_id) {

        let id = MediaLib.globalId + ' #block-tree-view';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');

        $(id).loader(str_loading);

        $.ajax({
            method: 'GET',
            url: url,
        })
            .done(function (html) {
                $(id).html(html);
                MediaLib.OpenTreeFolderById(folder_id);
            });
    }

    /**
     * Event sur le tree de dossier
     * @constructor
     */
    MediaLib.EventTreeFolder = function () {

        /**
         * Click sur un dossier
         */
        $(MediaLib.globalId + ' #tree-view-folder .caret').click(function () {

            let current = $(this);
            $(MediaLib.globalId + ' #tree-view-folder .caret, ' + MediaLib.globalId + ' #tree-view-folder .link-filter').each(function () {
                $(this).removeClass('activeNode');
            })

            let element = current.next(".nested");
            element.toggleClass('active');
            current.toggleClass('caret-down');
            current.addClass('activeNode');

            if (!current.hasClass('caret-down')) {
                current.parent().find(".caret-down").removeClass('caret-down');
                current.parent().find(".active").removeClass('active');
                return false;
            }

            let id = MediaLib.globalId + ' #right-block-folder';
            let url = current.data('url');
            if (url === undefined) {
                MediaLib.loadBlockFolder();
                return false;
            }

            let str_loading = $(id).data('loading');
            System.Ajax(url, id, true, str_loading);
        });

        /**
         * Event sur le tree, pour filtrer en fonction d'une image / vidéo etc...
         */
        $(MediaLib.globalId + ' #tree-view-folder li.link-filter').click(function () {
            let element = $(this).parent().prev();
            let id = MediaLib.globalId + ' #right-block-folder';
            let url = element.data('url');

            let filter = $(this).data('id');

            $(MediaLib.globalId + ' #tree-view-folder .link-filter').each(function () {
                $(this).removeClass('activeNode');
            })
            $(this).addClass('activeNode');

            if (url === undefined) {
                MediaLib.loadBlockFolder();
                return false;
            }

            let str_loading = $(id).data('loading');

            $(id).loader(str_loading);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (html) {
                    $(id).html(html);
                    $(MediaLib.globalId + ' #btn-render-media #' + filter).prop('checked', 'checked');
                });
        })
    }

    /**
     * Event sur le block médias
     * @constructor
     */
    MediaLib.EventBlockMedia = function () {

        /**
         * Permet de naviger dans les dossier depuis le fil d'arianne
         */
        $(MediaLib.globalId + ' #breadcrumb-folder-media .breadcrumb-item a').click(function () {

            let id = MediaLib.globalId + ' #right-block-folder';
            let url = $(this).attr('href');
            let str_loading = $(id).data('loading');
            System.Ajax(url, id, true, str_loading);

            id = $(this).data('id');
            $(MediaLib.globalId + ' #tree-view-folder .caret').each(function () {
                $(this).removeClass('activeNode');

                if (id === $(this).data('id')) {
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
        $(MediaLib.globalId + ' #btn-render-media input').click(function () {
            MediaLib.loadContentFolder();
        });

        /**
         * Event sur le choix d'affichage
         */
        $(MediaLib.globalId + ' #btn-render-render input').click(function () {
            MediaLib.loadContentFolder();
        });

        /**
         * Event sur le bouton de recherche
         */
        $(MediaLib.globalId + ' #btn-search-media').click(function () {
            MediaLib.loadContentFolder();
        });

        /**
         * Event sur le bouton pour créer / éditer un dossier
         */
        $(MediaLib.globalId + ' #btn-new-folder, ' + MediaLib.globalId + ' #btn-edit-folder').click(function () {
            MediaLib.loadModal($(this));
        })

        /**
         * Event sur le bouton pour supprimer un dossier
         */
        $(MediaLib.globalId + ' #btn-delete-folder').click(function () {
            MediaLib.loadModal($(this));
        })

        /**
         * Event sur le bouton pour créer / éditer un dossier
         */
        $(MediaLib.globalId + ' #btn-add-media').click(function () {
            MediaLib.loadModal($(this));
        })
    }

    /**
     * Permet de charger le contenu d'une popin
     * @param element
     */
    MediaLib.loadModal = function(element) {

        let url = element.data('url');
        let str_loading = element.data('loading');
        let id = System.adminBlockModalId;

        $('body').loader(str_loading);

        $.ajax({
            method: 'GET',
            url: url,
        })
            .done(function (html) {
                $('body').removeLoader(str_loading);
                $(System.adminBlockModalId).html(html);
            });
    }

    /**
     * Permet de charger le block contenant les info du dossier courant
     */
    MediaLib.loadBlockFolder = function () {
        let id = MediaLib.globalId + ' #right-block-folder';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    }

    /**
     * Permet de charger les medias d'un dossier
     */
    MediaLib.loadContentFolder = function () {

        let id = MediaLib.globalId + ' #block-content-folder';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');

        let data = MediaLib.getDataFilterFolder();

        let tmp_ref = 'btn-render-' + data['media'];
        $(MediaLib.globalId + ' #tree-view-folder li.link-filter').each(function () {
            if ($(this).data('id') === tmp_ref && $(this).data('folder') === data['folder-id']) {
                $(this).addClass('activeNode');
            } else {
                $(this).removeClass('activeNode');
            }
        })

        $(id).loader(str_loading);

        $.ajax({
            method: 'POST',
            data: {'media-filter': data},
            url: url,
        })
            .done(function (html) {
                $(id).html(html);
            });
    }

    /**
     * Récupère les données de filtre pour l'affichage des médias d'un dossier
     */
    MediaLib.getDataFilterFolder = function () {

        let tab = {'media' : 'all'};
        tab['folder-id'] = $(MediaLib.globalId + ' #btn-render-media').data('folder');
        $(MediaLib.globalId + ' #btn-render-media input').each(function () {
            if ($(this).prop('checked')) {
                tab['media'] = $(this).data('type')
            }
        });

        let render = "";
        $(MediaLib.globalId + ' #btn-render-render input').each(function () {
            if ($(this).prop('checked')) {
                tab['render'] = $(this).attr('id').split('-')[2]
            }
        });

        tab['search'] = $(MediaLib.globalId + ' #search-media').val();
        return tab;
    }

    /**
     * Event sur la popin d'ajout/Supression d'un dossier
     */
    MediaLib.createUpdateFolderEvent = function(modalFolder) {

        $('#modal-create-update-folder #form-folder').submit(function(e) {
            e.preventDefault();

            $('#modal-create-update-folder').loader($(this).data('loading'));

            $.ajax({
                method: 'POST',
                data: $(this).serialize(),
                url: $(this).attr('action'),
            })
                .done(function (html) {
                    modalFolder.hide();
                    $(System.adminBlockModalId).html(html);
                });

        });
    }

    /**
     * Action à faire une fois que l'update c'est bien passé
     */
    MediaLib.createUpdateFolderSuccess = function (folder_id, modalFolder) {

        MediaLib.loadTreeFolder(folder_id);
        setTimeout(function(){ modalFolder.hide(); }, 3000);

    }

    /**
     * Ouvre l'arbre de dossier en fonction d'un ID
     * @constructor
     */
    MediaLib.OpenTreeFolderById = function(id) {

        let id_block_folder = MediaLib.globalId + ' #right-block-folder';

        $(MediaLib.globalId + ' #tree-view-folder .caret').each(function () {

            if ($(this).data('id') === id)
            {
                $(this).addClass('activeNode');
                $(this).addClass('caret-down');
                let element = $(this).next(".nested");
                element.addClass('active');

                let url = $(this).data('url');
                url = url.slice(0, url.lastIndexOf('/')) + '/' + id;
                let str_loading = $(id_block_folder).data('loading');

                $(id_block_folder).loader(str_loading);

                $.ajax({
                    method: 'GET',
                    url: url,
                })
                    .done(function (html) {
                        $(id_block_folder).html(html);
                        $(MediaLib.globalId + ' #btn-render-media #btn-render-all').prop('checked', 'checked');
                    });

                let before = $(this).parent().parent().parent().children('span');
                MediaLib.RecursiveOpenFolder(before);
                return false;
            }

            /** Parcours de façon récursive l'ensemble des dossier **/
            MediaLib.RecursiveOpenFolder = function(element)
            {
                //element.addClass('activeNode');
                element.addClass('caret-down');
                let next = element.next(".nested");
                next.addClass('active');

                let before = element.parent().parent().parent().children('span');
                if(before !== undefined)
                {
                    //console.log(before);
                    MediaLib.RecursiveOpenFolder(before);
                }
                return false;
            }
        })
    }

    /**
     * Event sur le contenu d'un dossier
     * @constructor
     */
    MediaLib.EventContentFolder = function() {

        MediaLib.globalIdContentFolder = '#see-block-content-folder';

        /**
         * Click sur un dossier
         */
        $(MediaLib.globalIdContentFolder + ' .div-folder').click(function() {
            MediaLib.OpenTreeFolderById($(this).data('id'));
        });

        /**
         * Event lien editer folder
         */
        $(MediaLib.globalIdContentFolder + ' .btn-edit-folder').click(function() {
            MediaLib.loadModal($(this));
        });

        /**
         * Event lien delete folder
         */
        $(MediaLib.globalIdContentFolder + ' .btn-delete-folder').click(function() {
            MediaLib.loadModal($(this));
        });

        /**
         * Event lien editer media
         */
        $(MediaLib.globalIdContentFolder + ' .btn-edit-media').click(function() {
            MediaLib.loadModal($(this));
        });

        /**
         * Event lien supprimer media
         */
        $(MediaLib.globalIdContentFolder + ' .btn-delete-media').click(function() {
            MediaLib.loadModal($(this));
        });

        /**
         * Event lien supprimer media
         */
        $(MediaLib.globalIdContentFolder + ' .btn-info-media').click(function() {
            MediaLib.loadModal($(this));
        });

    }

    /**
     * Event sur la popin de supression d'un dossier
     * @param modal
     * @constructor
     */
    MediaLib.EventDeleteFolder = function(modal) {
        MediaLib.globalIdDeleteFolder = '#modale-delete-folder';

        /**
         * Event sur le click du bouton confirmer
         */
        $(MediaLib.globalIdDeleteFolder + ' #btn-confirm-delete-folder').click(function() {

            let url = $(this).data('url');
            let str_loading = $(this).data('loading');

            $(MediaLib.globalIdDeleteFolder + ' .modal-body').loader(str_loading);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (response) {
                    $(MediaLib.globalIdDeleteFolder + ' .modal-body').removeLoader();
                    $(MediaLib.globalIdDeleteFolder + ' .modal-body').html(response.msg);

                    let id = MediaLib.globalId + ' #right-block-folder';
                    System.Ajax(response.url, id, true, response.str_loading);
                    MediaLib.loadTreeFolder(response.id)

                    setTimeout(function(){
                        modal.toggle();
                    }, 1500);
                });
        })
    }

    /**
     * Event sur la popin d'ajout/Supression d'un dossier
     */
    MediaLib.UpdateMediaEvent = function(modal) {

        $('#modal-update-media #form-media-update').submit(function(e) {
            e.preventDefault();

            $('#modal-update-media').loader($(this).data('loading'));

            $.ajax({
                method: 'POST',
                data: $(this).serialize(),
                url: $(this).attr('action'),
            })
                .done(function (html) {
                    modal.hide();
                    $(System.adminBlockModalId).html(html);
                });

        });
    }

    /**
     * Event sur la popin de supression d'un media
     * @param modal
     * @constructor
     */
    MediaLib.EventDeleteMedia = function(modal) {
        MediaLib.globalIdDeleteMedia = '#modale-delete-media';

        /**
         * Event sur le click du bouton confirmer
         */
        $(MediaLib.globalIdDeleteMedia + ' #btn-confirm-delete-media').click(function() {

            let url = $(this).data('url');
            let str_loading = $(this).data('loading');

            $(MediaLib.globalIdDeleteMedia + ' .modal-body').loader(str_loading);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (response) {
                    $(MediaLib.globalIdDeleteMedia + ' .modal-body').removeLoader();
                    $(MediaLib.globalIdDeleteMedia + ' .modal-body').html(response.msg);

                    let id = MediaLib.globalId + ' #right-block-folder';
                    System.Ajax(response.url, id, true, response.str_loading);

                    setTimeout(function(){
                        modal.hide();
                    }, 1500);
                });
        })
    }
}