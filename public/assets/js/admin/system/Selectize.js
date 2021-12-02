let Selectize = {}

Selectize.selectTags = function(id) {

    let url = $(id).data('url');
    Selectize.tabColor = [];

    $(id).selectize({
        plugins: ["remove_button"],
        valueField: 'id',
        labelField: 'name',
        searchField: 'name',
        onItemAdd: function(value, item) {
            console.log(value);
            color = '#'+(Math.random()*0xFFFFFF<<0).toString(16);
            Selectize.tabColor[item.name] = color;
            console.log(Selectize.tabColor[item.name]);
        },
        options: [],
        maxItems: null,
        create: true,
        render: {
            option: function(item, escape) {

                color = item.color;
                if(Selectize.tabColor[item.name] === undefined && item.color === undefined)
                {
                    color = '#'+(Math.random()*0xFFFFFF<<0).toString(16);
                    Selectize.tabColor[item.name] = color;
                } else if(item.color === undefined)
                {
                    color =  Selectize.tabColor[item.name];
                }
                return '<div><span class="badge" style="background-color: ' + color + '">' + item.name + '</span></div>';
            },
            item: function(item, escape) {

                color = item.color;
                if(Selectize.tabColor[item.name] === undefined && item.color === undefined)
                {
                    color = '#'+(Math.random()*0xFFFFFF<<0).toString(16);
                    Selectize.tabColor[item.name] = color;
                }
                return '<span class="badge me-2" style="background-color: ' + color + '">' + item.name + '</div>';
            }
        },
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: url + '/' + encodeURIComponent(query),
                type: 'GET',
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(JSON.parse(res));
                }
            });
        }
    });

}