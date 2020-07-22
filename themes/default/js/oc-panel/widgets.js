$(function  () {
   var group = $("ul.sortable").sortable({
          group: 'sortable',
          delay: 350,
          onDrop: function (item, container, _super) {
            //first we execute the normal plugins behaviour
            _super(item, container);

            //we delay the save for 3 seconds @todo doesnt work
            //var save_placeholders = _debounce(function(item, container)
            //{
                var item_id = item.attr("id");
                var placeholder = '';
                var data = {};
                $('.sortable').each(function() {
                    val = $(this).sortable().sortable('serialize').get();

                    //empty UL
                    if (val == '[object HTMLUListElement]') {
                        val = '';
                    }
                    else{
                        //array of values
                        val = val[0].split(',');

                        //we get the placeholder where we drop
                        if ($.inArray(item_id,val)>-1)
                            placeholder = this.id;
                    }

                    //generating the array to send to the server
                    data[this.id] = val;
                });

                //item update the placeholder form input
                var input_placeholder = $('#form_widget_'+item_id+' [name=placeholder]');
                input_placeholder.val(placeholder);
                input_placeholder.trigger("liszt:updated");

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

            //},300);


          },
          serialize: function (parent, children, isContainer) {
            return isContainer ? children.join() : parent.attr("id");
          }
    });

})
