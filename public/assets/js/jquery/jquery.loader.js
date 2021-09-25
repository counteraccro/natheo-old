/**
 * Plugin Jquery Loader
 * @Auteur Gourdon Aymeric
 * @version 1.0
 */

(function($) {

    let element = '';

    $.fn.loader = function(str_loading)
    {
        let height = $(this).height();
        let width = $(this).width();


        if(str_loading === '' || str_loading == null)
        {
            str_loading = 'Loading...';
        }

        element = $('<div style="position:absolute; width:' + width + 'px; height:' + height + 'px; background-color:#FAFAFA; opacity:0.7; z-index:1000; filter:alpha(opacity=70);">' +
            '<div class="text-center align-items-center" style="z-index:1001;color:black;margin-top:'+ ((height/2)-10) +'px;">' +
            '<div class="spinner-border spinner-border-sm text-primary" role="status">' +
            '<span class="sr-only">Loading...</span>' +
            '</div>' +
            '<i> ' + str_loading + '</i> ' +
            '</div></div>');

        $(this).prepend(element);
    };

    $.fn.removeLoader = function()
    {
        element.remove();
    };
})(jQuery);