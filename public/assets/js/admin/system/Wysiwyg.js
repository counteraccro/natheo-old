
/**
 *  JS pour l'éditeur de texte
 *  @author Gourdon Aymeric
 *  @version 1.0
 **/
let Wysiwyg = {}

/**
 * Charge l'éditeur standard sans options
 * @param id
 * @constructor
 */
Wysiwyg.DefaultEditor = function (id) {
    $(id).summernote({
        height: 170
    });
}