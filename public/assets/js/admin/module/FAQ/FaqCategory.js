/**
 *  JS gestion des faq Category pour l'admin
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let FAQCategory = {}

FAQCategory.Launch = function () {

    FAQCategory.globalId = '#admin-faq-globale';

    /**
     * Charge la liste des tags
     * @constructor
     */
    FAQCategory.LoadListing = function () {
        let id = FAQCategory.globalId + ' .card-body';
        let url = $(id).data('url');

        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    };

    /**
     * Event sur le listing des catégories
     * @param globalId
     * @constructor
     */
    FAQCategory.EventListing = function(globalId)
    {
        FAQCategory.globalIdListing = globalId;

        $(FAQCategory.globalIdListing + ' .btn-faq-cat-change-position').click(function() {

            let url = $(this).data('url');
            let str_loading = $(this).data('loading');

            $(FAQCategory.globalIdListing).loader(str_loading);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (response) {
                    FAQCategory.LoadListing();
                });
            return false;
        })

        /**
         * Event pour la suppression d'une Categorie
         */
        $(FAQCategory.globalIdListing + ' .btn-delete-faq-cat').click(function() {

            let url = $(this).data('url');
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
        });
    }

    /**
     * Event sur la création / Edition d'une catégorie
     * @param globalId
     * @param frontUrl
     * @param currentLocal
     * @param action
     * @param urlCheckSlug
     * @constructor
     */
    FAQCategory.EventCreateUpdate = function (globalId, frontUrl, currentLocal, action, urlCheckSlug) {

        FAQCategory.globalIdCreateUpdate = globalId;

        $(FAQCategory.globalIdCreateUpdate + ' input.titre').keyup(function () {
            FAQCategory.CreateSlug($(this));
        })

        $(FAQCategory.globalIdCreateUpdate + ' input.titre').change(function () {
            FAQCategory.CheckUniqueSlug($(this));
        })

        /**
         * Met à jour le slug
         * @constructor
         */
        FAQCategory.UpdateSlug = function () {
            $(FAQCategory.globalIdCreateUpdate + ' input.titre').each(function () {
                FAQCategory.CreateSlug($(this));
            })
        }

        /**
         * Permet de créer un slug
         * @param element
         * @constructor
         */
        FAQCategory.CreateSlug = function (element) {
            let slug = System.stringToSlug(element.val());
            $(FAQCategory.globalIdCreateUpdate + ' #slug-' + element.data('nb')).val(slug);

            slug = frontUrl.replace('slug', slug);
            slug = slug.replace(currentLocal, element.data('local'))

            let help = 'Url : ' + slug;
            if (action === "edit") {
                help = 'Url : <a href="' + slug + '" target="_blank">' + slug + '</a>';
            }

            $(FAQCategory.globalIdCreateUpdate + ' #' + element.attr('id') + '_help').html(help);
        }

        /**
         * Vérifie si le slug est unique
         * @param element
         * @constructor
         */
        FAQCategory.CheckUniqueSlug = function (element) {
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
                        $(FAQCategory.globalIdCreateUpdate + ' #' + element.attr('id') + '_help').html(response.msg).addClass('text-danger');
                        $(FAQCategory.globalIdCreateUpdate + ' #faq_category_valider').prop( "disabled", true );
                    }
                    else {
                        element.removeClass('is-invalid');
                        $(FAQCategory.globalIdCreateUpdate + ' #' + element.attr('id') + '_help').removeClass('text-danger');
                        $(FAQCategory.globalIdCreateUpdate + ' #faq_category_valider').prop( "disabled", false );
                    }
                });
        }
    }

    /**
     * Event sur la popin de supression d'une FAQ Categorie
     * @param modal
     * @constructor
     */
    FAQCategory.EventDelete = function(modal) {
        FAQCategory.globalIdDeleteTheme = '#modale-delete-faq-cat';

        /**
         * Event sur le click du bouton confirmer
         */
        $(FAQCategory.globalIdDeleteTheme + ' #btn-confirm-delete-faq-cat').click(function() {

            let url = $(this).data('url');
            let str_loading = $(this).data('loading');
            let redirect = $(this).data('redirect');

            $(FAQCategory.globalIdDeleteTheme + ' .modal-body').loader(str_loading);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (response) {
                    $(FAQCategory.globalIdDeleteTheme + ' .modal-body').removeLoader();
                    $(FAQCategory.globalIdDeleteTheme + ' .modal-body').html(response.msg);

                    setTimeout(function(){
                        modal.toggle();
                        document.location.href= redirect;
                    }, 1500);
                });
        })
    }
}