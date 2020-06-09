$(function(){
    if($('[data-toggle="datepicker"]').length !== 0){
        $('[data-toggle="datepicker"]').datepicker();
    }

    $('.btn-fixed').click(function(event) {
        event.preventDefault();
        $(this).addClass('active');
        $('.btn-percentage').removeClass('active');
        $('input[name="discount_percentage"]').closest('.form-group ').addClass('hidden');
        $('input[name="discount_amount"]').closest('.form-group ').removeClass('hidden');
    });

    $('.btn-percentage').click(function(event) {
        event.preventDefault();
        $(this).addClass('active');
        $(".btn-fixed").removeClass('active');
        $('input[name="discount_amount"]').closest('.form-group ').addClass('hidden');
        $('input[name="discount_percentage"]').closest('.form-group ').removeClass('hidden');
    });

    $('#csv_upload').click( function() {
        //check whether browser fully supports all File API
        if (window.File && window.FileReader && window.FileList && window.Blob)
        {
            //get the file size and file type from file input field
            var fsize = $('#csv_file_coupons')[0].files[0].size;

            if(fsize>1048576) //do something if file size more than 1 mb (1048576)
            {
                alert(fsize +" bites\nToo big!");
                event.preventDefault();
            }
        }
    });

});
