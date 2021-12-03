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
    Tag.CreateUpdate = function () {

        Tag.createUpdateId = '#admin-create-update-tag';

        $(Tag.createUpdateId + ' #render-exemple-tag').css({"background-color": $(Tag.createUpdateId + ' #tag_color').val()});
        $(Tag.createUpdateId + ' #render-exemple-tag').html($(Tag.createUpdateId + ' #tag_name').val());

        /**
         * Au changement du short label, on met à jour l'exemple
         */
        $(Tag.createUpdateId + ' #tag_name').keyup(function () {
            $(Tag.createUpdateId + ' #render-exemple-tag').html($(this).val());
        });

        /**
         * AU changement de la couleur on met à jour l'exemple
         */
        $(Tag.createUpdateId + ' #tag_color').change(function () {
            $(Tag.createUpdateId + ' #render-exemple-tag').css({"background-color": $(this).val()});
        });
    };

    /**
     * Permet de selectionner un ou plusieurs tags
     * @param id
     * @constructor
     */
    Tag.SelectTagForElement = function (id, url_save) {

        let idOptionList = id + '-options';
        url_save = url_save.substring(0, url_save.length - 1);

        /**
         * Retourne une option d'une dataList si elle existe
         * @param inputValue
         * @param idOptionList
         * @constructor
         */
        Tag.GetOptionInDataList = function (inputValue, idOptionList) {

            let result = null;
            let x = $(idOptionList + ' option');
            $(x).each(function () {
                if ($(this).val() === inputValue) {
                    result = $(this);
                }
            })
            return result;
        }

        /**
         * A chaque caractère saisi
         */
        $(id).keypress(function () {

            let url = $(this).data('url');
            let newStr = $(this).data('new');
            let value = encodeURIComponent($(this).val())

            $.ajax({
                method: 'GET',
                url: url + '/' + value,
            })
                .done(function (response) {
                    let tab = JSON.parse(response);

                    $(idOptionList).html('');

                    if (tab.length > 0) {
                        $.each(tab, function (key, value) {
                            $(idOptionList).append('<option data-id="' + value.id + '" value="' + value.name + '">')
                        });
                    }
                })
        });

        /**
         * Bin du champ de recherche de tag
         */
        $(id).bind('input', function () {
            let element = Tag.GetOptionInDataList($(id).val(), idOptionList);
            if (element != null) {

                $.ajax({
                    method: 'GET',
                    url: url_save + element.data('id'),
                })
                    .done(function (response) {

                    })

            }
        });
    }

}