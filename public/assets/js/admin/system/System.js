let System = {};

System.Ajax = function (url, id_done, loader = true, str_loader = "") {
    if (loader) {
        $(id_done).loader(str_loader);
    }

    $.ajax({
        method: 'GET',
        url: url,
    })
        .done(function (html) {
            $(id_done).html(html);
        });
};

System.Paginate = function() {

    $('.pagination .page-item .page-link').click(function() {
        let url = $(this).attr('href');
        let str_loader = $('.pagination').data('loading');
        let id = $('.pagination').data('id');

        System.Ajax(url, id, true, str_loader);

        return false;
    });
}