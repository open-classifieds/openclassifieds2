$(function  () {
    var group = $("ol.plholder").sortable({
        group: 'plholder',
        delay: 100,
        onDrop: function (item, container, _super) {
            //first we execute the normal plugins behaviour
            _super(item, container);

            //where we drop the category
            var parent = $(item).parent();

            //values of the list
            val = $(parent).sortable().sortable('serialize').get();
            val = val[0].split(',');

            //how deep are we? we don't need it we process it in the php
            var deep = $(item).parentsUntil($("ol.plholder"),'ol')['length'];  

            //building data to send
            var data = {
                  "id_category" : $(item).data('id'),
                  "id_category_parent" : $(parent).data('id'),
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
                    $("ol.plholder").sortable('disable');
                    $('ol.plholder').animate({opacity: '0.5'});
                },
                data: data,
                success: function(text) {
                    $('#ajax_result').text(text).removeClass().addClass("label label-success");
                    $("ol.plholder").sortable('enable');
                    $('ol.plholder').animate({opacity: '1'});
                }               
            });
             
        },
        serialize: function (parent, children, isContainer) {
             return isContainer ? children.join() : parent.attr("id");
        },

    })
})

$(function(){
    $(".index-delete").click(function(event) {
        var href = $(this).attr('href');
        var title = $(this).attr('title');
        var text = $(this).data('text');
        var id = $(this).data('id');
        var confirmButtonText = $(this).data('btnoklabel');
        var cancelButtonText = $(this).data('btncancellabel');
        event.preventDefault();
        swal({
            title: title,
            text: text,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            allowOutsideClick: true,
        },
        function(){
            $("ol.plholder").sortable('disable');
            $.ajax({ url: href,
                }).done(function ( data ) {
                    $('#'+id).hide("slow");
                    window.location.href = window.location.href;
            });
        });
    }); 
});
