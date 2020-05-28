$(function  () {
    var group = $("ul.sortable").sortable({
        group: 'sortable',
        delay: 350,
        onDrop: function (item, container, _super) {
            //first we execute the normal plugins behaviour
            _super(item, container);

            //where we drop the category
            var parent = $(item).parent();

            //values of the list
            val = $(parent).sortable().sortable('serialize').get();

            //empty UL
            if (val == '[object HTMLUListElement]') {
                val = '';
            }
            else{
                //array of values
                val = val[0].split(',');
            }

            //generating the array to send to the server
            var data = {};
            data['order'] = val;

            //saving the order
            $.ajax({
                type: "GET",
                url: $('#ajax_result').data('url'),
                beforeSend: function(text) {
                    $('#ajax_result').text('Saving').removeClass().addClass("label label-warning");
                    $("ul.sortable").sortable('disable');
                    $('ul.sortable').animate({opacity: '0.5'});
                },
                data: data,
                success: function(text) {
                    $('#ajax_result').text(text).removeClass().addClass("label label-success");
                    $("ul.sortable").sortable('enable');
                    $('ul.sortable').animate({opacity: '1'});
                }
            });


        },
        serialize: function (parent, children, isContainer) {
             return isContainer ? children.join() : parent.attr("id");
        },

    })
})
