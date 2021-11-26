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
}