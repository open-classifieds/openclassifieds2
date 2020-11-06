$(function(){

    //sceditor for validation, updates iframe on submit
    $("button[name=submit]").click(function(){
        $("textarea[name=description]").data("sceditor").updateOriginal();
    });

    //select2 enable/disable
    $('select').select2({
        "language": "es"
    });
    $('select').each(function(){
        if($(this).hasClass('disable-select2')){
            $(this).select2('destroy');
        }
    });
    // Fixes select2 on bootstrap modals and iOS devices
    $('#register-modal select').each(function(){
        if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream)
        {
            $(this).select2('destroy');
        }
    });
    // Location fuzzy search
    $('.ajax-location-search').each(function(){
        $(this).select2('destroy').select2({
            ajax: {
                url: $(this).data('apiurl'),
                dataType: 'json',
                type: "GET",
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term
                    };
                },
                processResults: function (data) {
                    var res = data.locations.map(function (item) {
                        return { id: item.seoname, text: item.name };
                    });
                    return {
                        results: res
                    };
                },
                cache: true
            },
            minimumInputLength: 2,
        });
    });
    //select2 responsive width
    $(window).on('resize', function() {
        $('select').each(function(){
            var width = $(this).parent().width();
            $(this).siblings('.select2-container').css({'width':width});
        });
    }).trigger('resize');

    $('input, select, textarea, .btn').tooltip();

    //datepicker in case date field exists
    if($('.cf_date_fields').length != 0){
        $('.cf_date_fields').datepicker({
            autoclose: true
        });}

	$('.tips').popover();

	$('.slider_subscribe').slider();

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');

    $(window).load(function(){
        $('#accept_terms_modal').modal('show');
    });

    //list / grit swap
    $('#list').click(function(event){
        $('.ad_griditem').addClass('ad_listitem');
        $('.ad_griditem').removeClass('ad_griditem col-lg-4 col-md-4 col-sm-4 col-xs-12');
        $(this).addClass('active');
        $('#grid').removeClass('active');
        setCookie('list/grid',1,10);
    });

    $('#grid').click(function(event){
        $('.ad_listitem').addClass('ad_griditem col-lg-4 col-md-4 col-sm-4 col-xs-12');
        $('.ad_listitem').removeClass('ad_listitem');
        $(this).addClass('active');
        $('#list').removeClass('active');
        setCookie('list/grid',0,10);
    });

    if(getCookie('list/grid') == 1)
        $("#list").trigger("click");
    else if(getCookie('list/grid') == 0)
        $("#grid").trigger("click");
    else if(getCookie('list/grid') == 2)
        $("#minimal").trigger("click");
    else if(getCookie('list/grid') == null){
        if($('#listgrid').data('default') == 1)
            $("#list").trigger("click");
        else if($('#listgrid').data('default') == 0)
            $("#grid").trigger("click");
        else if($('#listgrid').data('default') == 2)
            $("#minimal").trigger("click");
    }

});

$(function(){
    var maxHeight = 0;
    $(".latest_ads").each(function() {
        if ($(this).outerHeight() > maxHeight) {
            maxHeight = $(this).outerHeight();
        }
    }).height(maxHeight);
});
