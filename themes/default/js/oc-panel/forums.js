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
            val = val[0].split(',');

            //how deep are we? we don't need it we process it in the php
            var deep = $(item).parentsUntil($("ul.sortable"),'ul')['length'];

            //building data to send
            var data = {
                  "id_forum" : $(item).data('id'),
                  "id_forum_parent" : $(parent).data('id'),
                  //"order" : $.inArray($(item).attr('id'),val),
                  "deep" : deep,
                  "brothers" : val,
                };

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
