let System = {};

System.Ajax = function (url, id_done, loader = true, str_loader = "", method = 'GET') {
    if (loader) {
        $(id_done).loader(str_loader);
    }

    $.ajax({
        method: method,
        url: url,
    })
        .done(function (html) {
            $(id_done).html(html);
        });
}