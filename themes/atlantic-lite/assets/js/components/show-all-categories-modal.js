$('.show-all-categories').on('click',function(event){
    event.preventDefault();
    $.ajax({
        url: $('#modal-home-categories').data('apiurl'),
        data: {
            "id_category_parent": $(this).data('cat-id'),
            "sort": 'order',
        },
        success: function(result) {
            $('#modal-home-categories .modal-body .list-group').empty();
            $('#modal-home-categories').modal('show');
            $.each(result.categories, function (idx, category) {
                $("#modal-home-categories .modal-body .list-group").append('<a href="/' + category.seoname + '" class="list-group-item list-group-item-action">' + category.translate_name + '</a>');
            });
        }
    });
});
