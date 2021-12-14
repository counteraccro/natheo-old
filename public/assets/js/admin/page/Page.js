/**
 *  JS gestion pages
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/

let Page = {};

Page.Launch = function() {

    /**
     * Event sur la création / édition d'une page
     * @constructor
     */
    Page.EventCreateUpdate = function(globalId, frontUrl, currentLocal, action, urlCheckSlug) {

        Page.createUpdateGlobalId = globalId;

        $(Page.createUpdateGlobalId + ' input.page-title').keyup(function () {
            Page.CreateSlug($(this));
        })

        $(Page.createUpdateGlobalId + ' input.page-title').change(function () {
            Page.CheckUniqueSlug($(this));

            let nb = $(this).data('nb');
            $(Page.createUpdateGlobalId + ' #navigationTitle-' +nb).val($(this).val())
        })

        $(Page.createUpdateGlobalId + ' .active-translate').each(function() {

            let nb = $(this).data('nb');
            if(!$(this).prop('checked'))
            {
                //$(Page.createUpdateGlobalId + ' .page-translate-input-' + nb).prop('disabled', true);
                $(Page.createUpdateGlobalId + ' .page-translate-input-' + nb).parent('.mb-3').hide();
                if (typeof $(Page.createUpdateGlobalId + ' .page-translate-input-' + nb).data('default') === undefined) {
                    $(Page.createUpdateGlobalId + ' .page-translate-input-' + nb).val( $(Page.createUpdateGlobalId + ' .page-translate-input-' + nb).data('default'));
                }
                $(Page.createUpdateGlobalId + ' .msg-info-disabled-' + nb).show();
            }
        });

        /**
         * Click pour ouvrir la modale de choix du template
         */
        $(Page.createUpdateGlobalId + ' #btn-modal-select-template').click(function() {

            let url = $(this).attr('href');
            let str_loading = $(this).data('loading');
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

            return false;
        });


        /** Active ou désactive le bloc de la langue courante */
        $(Page.createUpdateGlobalId + ' .active-translate').change(function() {
            let nb = $(this).data('nb');
            let check = $(this).prop('checked');

            $(Page.createUpdateGlobalId + ' .page-translate-input-' + nb).each(function() {
                let val = $(this).data('value');

                if(!check)
                {
                    $(this).parent('.mb-3').hide();
                    $(Page.createUpdateGlobalId + ' .msg-info-disabled-' + nb).show();
                    if (typeof $(this).data('default') === undefined) {
                        $(this).val( $(this).data('default'));
                    }
                }
                else {
                    $(this).parent('.mb-3').show();
                    $(Page.createUpdateGlobalId + ' .msg-info-disabled-' + nb).hide();
                    if (typeof $(this).data('default') === undefined) {
                        $(this).val('a');
                    }
                    else {
                        $(this).val($(this).data('value'));
                    }

                }
            });
        })

        /**
         * Met à jour le slug
         * @constructor
         */
        Page.UpdateSlug = function () {
            $(Page.createUpdateGlobalId + ' input.page-title').each(function () {
                Page.CreateSlug($(this));
            })
        }

        /**
         * Permet de créer un slug
         * @param element
         * @constructor
         */
        Page.CreateSlug = function (element) {
            let slug = System.stringToSlug(element.val());
            $(Page.createUpdateGlobalId + ' #slug-' + element.data('nb')).val(slug);

            slug = frontUrl.replace('slug', slug);
            slug = slug.replace(currentLocal, element.data('local'))

            let help = 'Url : ' + slug;
            if (action === "edit") {
                help = 'Url : <a href="' + slug + '" target="_blank">' + slug + '</a>';
            }

            $(Page.createUpdateGlobalId + ' #' + element.attr('id') + '_help').html(help);
        }

        /**
         * Vérifie si le slug est unique
         * @param element
         * @constructor
         */
        Page.CheckUniqueSlug = function (element) {
            let slug = System.stringToSlug(element.val());

            if(slug === "")
            {
                slug = "!";
            }

            url = urlCheckSlug.replace('pslug', slug);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (response) {
                    if(response.msg !== "")
                    {
                        element.addClass('is-invalid');
                        $(Page.createUpdateGlobalId + ' #' + element.attr('id') + '_help').html(response.msg).addClass('text-danger');
                        $(Page.createUpdateGlobalId + ' #page_valider').prop( "disabled", true );
                    }
                    else {
                        element.removeClass('is-invalid');
                        $(Page.createUpdateGlobalId + ' #' + element.attr('id') + '_help').removeClass('text-danger');
                        $(Page.createUpdateGlobalId + ' #page_valider').prop( "disabled", false );
                    }
                });
        }
    }

    /**
     * Permet de charger le template de base pour la page
     * @constructor
     */
    Page.LoadTemplate = function()
    {
        let id = '#content-template';
        let url = $(id).data('url');
        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    }

    /**
     * Event sur la selection d'un template
     * @param globalId
     * @constructor
     */
    Page.EventSelectTemplate = function(globalId) {
        Page.selectTemplateGlobalId = globalId;
        $(Page.selectTemplateGlobalId + ' #txt-success').hide();

        /**
         * Event sur le choix d'un template
         */
        $(Page.selectTemplateGlobalId + ' input[type="radio"]').change(function() {

            let url = $(Page.selectTemplateGlobalId).data('url-select')
            let str_loading = $(Page.selectTemplateGlobalId).data('loading');
            let val = $(this).val();

            url = url + '/' + val;

            $(Page.selectTemplateGlobalId + ' .modal-dialog').loader(str_loading);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (html) {
                    $(Page.selectTemplateGlobalId + ' .modal-dialog').removeLoader();
                    $(Page.selectTemplateGlobalId + ' #txt-success').show();

                    setTimeout(() => {   $(Page.selectTemplateGlobalId + ' #txt-success').hide(); }, 2000);

                    Page.LoadTemplate();
                });

        })
    }

    /**
     * Event sur la partie content de la création d'une page
     * @param globalId
     * @constructor
     */
    Page.EventContent = function(globalId)
    {
        Page.eventContentGlobalId = globalId;
    }
}