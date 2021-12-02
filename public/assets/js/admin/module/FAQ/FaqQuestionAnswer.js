/**
 *  JS gestion des faq questions / réponses pour l'admin
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let FAQQuestionAnswer = {}

FAQQuestionAnswer.Launch = function () {

    FAQQuestionAnswer.globalId = '#admin-faq-qr-globale';

    /**
     * Charge la liste des tags
     * @constructor
     */
    FAQQuestionAnswer.LoadListing = function () {
        let id = FAQQuestionAnswer.globalId + ' .card-body';
        let url = $(id).data('url');

        let str_loading = $(id).data('loading');
        System.Ajax(url, id, true, str_loading);
    };

    /**
     * Event sur le listing des catégories
     * @param globalId
     * @constructor
     */
    FAQQuestionAnswer.EventListing = function (globalId) {
        FAQQuestionAnswer.globalIdListing = globalId;

        /*$(FAQQuestionAnswer.globalIdListing + ' .btn-faq-cat-change-position').click(function() {

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
        })*/

        /**
         * Event pour la suppression d'une Categorie
         */
        /*$(FAQQuestionAnswer.globalIdListing + ' .btn-delete-faq-cat').click(function() {

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
        });*/
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
    FAQQuestionAnswer.EventCreateUpdate = function (globalId, frontUrl, currentLocal, action, urlCheckSlug) {

        FAQQuestionAnswer.globalIdCreateUpdate = globalId;
        let id = $(FAQQuestionAnswer.globalIdCreateUpdate + ' #select-faq-cat').val();
        FAQQuestionAnswer.LoadListePosition(id);

        $(FAQQuestionAnswer.globalIdCreateUpdate + ' input.question').keyup(function () {
            FAQQuestionAnswer.CreateSlug($(this));
        })

        $(FAQQuestionAnswer.globalIdCreateUpdate + ' input.question').change(function () {
            FAQQuestionAnswer.CheckUniqueSlug($(this));
        })

        $(FAQQuestionAnswer.globalIdCreateUpdate + ' #select-faq-cat').change(function () {
            let id = $(this).val();
            FAQQuestionAnswer.LoadListePosition(id);
        });

        $(FAQQuestionAnswer.globalIdCreateUpdate + ' .active-translate').each(function () {

            let nb = $(this).data('nb');
            if (!$(this).prop('checked')) {
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #question-' + nb).prop('disabled', true);
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #answer-' + nb).prop('disabled', true);
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #answer-' + nb).summernote("disable");
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #page-title-' + nb).prop('disabled', true)
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-description-' + nb).prop('disabled', true);
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-keyword-' + nb).prop('disabled', true);
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-extra-metatags-' + nb).prop('disabled', true);
            }
        })

        $(FAQQuestionAnswer.globalIdCreateUpdate + ' .active-translate').change(function () {
            let nb = $(this).data('nb');
            if (!$(this).prop('checked')) {
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #question-' + nb).prop('disabled', true).val('');
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #answer-' + nb).prop('disabled', true).val('');
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #answer-' + nb).summernote("disable");
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #answer-' + nb).summernote('code', '')
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #page-title-' + nb).prop('disabled', true).val('');
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-description-' + nb).prop('disabled', true).val('');
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-keyword-' + nb).prop('disabled', true).val('');
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-extra-metatags-' + nb).prop('disabled', true).val('');
                ;
            } else {

                let titreVal = $(FAQQuestionAnswer.globalIdCreateUpdate + ' #question-' + nb).data('value');
                let descriptionVal = $(FAQQuestionAnswer.globalIdCreateUpdate + ' #answer-' + nb).data('value');
                let pageitle = $(FAQQuestionAnswer.globalIdCreateUpdate + ' #page-title-' + nb).data('value');
                let metaDescription = $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-description-' + nb).data('value');
                let metaKeyword = $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-keyword-' + nb).data('value');
                let metaExtraMetatags = $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-extra-metatags-' + nb).data('value');

                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #question-' + nb).prop('disabled', false).val(titreVal);
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #answer-' + nb).prop('disabled', false).val(descriptionVal);
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #answer-' + nb).summernote("enable");
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #answer-' + nb).summernote('code', descriptionVal);

                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #page-title-' + nb).prop('disabled', false).val(pageitle);
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-description-' + nb).prop('disabled', false).val(metaDescription);
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-keyword-' + nb).prop('disabled', false).val(metaKeyword);
                $(FAQQuestionAnswer.globalIdCreateUpdate + ' #meta-extra-metatags-' + nb).prop('disabled', false).val(metaExtraMetatags);
            }
        });

        /**
         * Event sur le listing des positions (appelé en ajax)
         * @constructor
         */
        FAQQuestionAnswer.EventListePosition = function() {

            $('#position-faq-qr').val($(FAQQuestionAnswer.globalIdCreateUpdate + ' #faq_question_answer_position').val());

            $(FAQQuestionAnswer.globalIdCreateUpdate + ' #faq_question_answer_position').change(function() {
                $('#position-faq-qr').val($(this).val());
            })
        }

        /**
         * Met à jour le slug
         * @constructor
         */
        FAQQuestionAnswer.UpdateSlug = function () {
            $(FAQQuestionAnswer.globalIdCreateUpdate + ' input.titre').each(function () {
                FAQQuestionAnswer.CreateSlug($(this));
            })
        }

        /**
         * Permet de créer un slug
         * @param element
         * @constructor
         */
        FAQQuestionAnswer.CreateSlug = function (element) {
            let slug = System.stringToSlug(element.val());
            $(FAQQuestionAnswer.globalIdCreateUpdate + ' #slug-' + element.data('nb')).val(slug);

            slug = frontUrl.replace('slug', slug);
            slug = slug.replace(currentLocal, element.data('local'))

            let help = 'Url : ' + slug;
            if (action === "edit") {
                help = 'Url : <a href="' + slug + '" target="_blank">' + slug + '</a>';
            }

            $(FAQQuestionAnswer.globalIdCreateUpdate + ' #' + element.attr('id') + '_help').html(help);
        }

        /**
         * Vérifie si le slug est unique
         * @param element
         * @constructor
         */
        FAQQuestionAnswer.CheckUniqueSlug = function (element) {
            let slug = System.stringToSlug(element.val());

            if (slug === "") {
                slug = "!";
            }

            url = urlCheckSlug.replace('pslug', slug);

            $.ajax({
                method: 'GET',
                url: url,
            })
                .done(function (response) {
                    if (response.msg !== "") {
                        element.addClass('is-invalid');
                        $(FAQQuestionAnswer.globalIdCreateUpdate + ' #' + element.attr('id') + '_help').html(response.msg).addClass('text-danger');
                        $(FAQQuestionAnswer.globalIdCreateUpdate + ' #faq_category_valider').prop("disabled", true);
                    } else {
                        element.removeClass('is-invalid');
                        $(FAQQuestionAnswer.globalIdCreateUpdate + ' #' + element.attr('id') + '_help').removeClass('text-danger');
                        $(FAQQuestionAnswer.globalIdCreateUpdate + ' #faq_category_valider').prop("disabled", false);
                    }
                });
        }
    }

    /**
     * Charge la liste des positions en fonction d'une catégorie de FAQ
     * @param id
     */
    FAQQuestionAnswer.LoadListePosition = function (id) {
        let blocList = '#faq-question-answer-position-block';
        let urlListPosition = $(blocList).data('url');

        url = urlListPosition.replace('0', id);
        $(blocList).loader();

        $.ajax({
            method: 'GET',
            url: url,
        })
            .done(function (html) {
                $(blocList).html(html);
                $(blocList).removeLoader();
            })
    }

    /**
     * Event sur la popin de supression d'une FAQ Categorie
     * @param modal
     * @constructor
     */
    FAQQuestionAnswer.EventDelete = function (modal) {
        FAQQuestionAnswer.globalIdDeleteTheme = '#modale-delete-faq-cat';

        /**
         * Event sur le click du bouton confirmer
         */
        /*$(FAQCategory.globalIdDeleteTheme + ' #btn-confirm-delete-faq-cat').click(function() {

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
        })*/
    }
}