$(function(){
    $('.drag-action').click(function(event) {
        event.preventDefault();
        var $cf = $(this);
        var action = $cf.closest('ol').data('id')
        if (action == 1)
        {
            swal({
                title: $cf.data('remove-title'),
                type: "info",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: $cf.data('btnoklabel'),
                cancelButtonText: $cf.data('btncancellabel'),
                allowOutsideClick: true,
            },
            function(){
                $.ajax({ url: $cf.data('remove-url'),
                    }).done(function ( data ) {
                        $('#' + $cf.data('id')).slideUp('fast', function() {
                            $cf.closest('li').find('.drag-name > .fa').remove();
                            $('ol[data-id="2"]').append($cf.attr('href', $cf.data('add-url')).find('.fa').removeClass('fa-minus').addClass('fa-plus').closest('li').slideDown('fast'));
                            $cf.removeClass('index-delete').addClass('index-add');
                            if ($('ol[data-id="2"] li').length > 0) $('p[data-add-label]').removeClass('hidden');
                    });
                });
            });
        }
        else
        {
            $.ajax({ url: $cf.data('add-url'),
                }).done(function ( data ) {
                    $('#' + $cf.data('id')).slideUp('fast', function() {
                        $cf.closest('li').find('.drag-name').prepend('<i class="text-success fa fa-check"></i>');
                        $('ol[data-id="1"]').append($cf.attr('href', $cf.data('remove-url')).find('.fa').removeClass('fa-plus').addClass('fa-minus').closest('li').slideDown('fast'));
                        $cf.removeClass('index-add').addClass('index-delete');
                        $('p[data-added-label]').removeClass('hidden');
                        if ($('ol[data-id="1"] li').length > 0) $('p[data-added-label]').removeClass('hidden');
                });
            });
        }
    });
});
