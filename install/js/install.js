jQuery.fn.animateAuto = function(prop, speed, callback){
    var elem, height, width;
    return this.each(function(i, el){
        el = jQuery(el), elem = el.clone().css({"height":"auto","width":"auto"}).appendTo("body");
        height = elem.css("height"),
        width = elem.css("width"),
        elem.remove();

        if(prop === "height")
            el.animate({"height":height}, speed, callback);
        else if(prop === "width")
            el.animate({"width":width}, speed, callback);  
        else if(prop === "both")
            el.animate({"width":width,"height":height}, speed, callback);
    });
}

$(function(){
    var panelDB =   $('.panel-2').scotchPanel({
                        containerSelector: '.panel-1',
                        direction: 'right',
                        duration: 300,
                        transition: 'ease',
                        clickSelector: '.toggle-panel-1',
                        distanceX: '100%',
                        enableEscapeKey: false,
                        beforePanelOpen: function() {
                            var height = $('.panel-2 .scotch-panel-wrapper:first').height();
                            $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
                        },
                        beforePanelClose: function() {
                            $('.panel-1 .scotch-panel-wrapper:first').css({height:'auto'});
                            var height = $('.panel-1 .scotch-panel-wrapper:first').height();
                            $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
                        },
                    });
    
    var panelSite =   $('.panel-3').scotchPanel({
                        containerSelector: '.panel-2',
                        direction: 'right',
                        duration: 300,
                        transition: 'ease',
                        clickSelector: '.toggle-panel-2',
                        distanceX: '100%',
                        enableEscapeKey: false,
                        beforePanelOpen: function() {
                            var height = $('.panel-3 .scotch-panel-wrapper:first').height();
                            $('.panel-2 .scotch-panel-wrapper:first').animate({height:height}, 300);
                            $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
                        },
                        beforePanelClose: function() {
                            $('.panel-2 .scotch-panel-wrapper:first').css({height:'auto'});
                            var height = $('.panel-2 .scotch-panel-wrapper:first').height();
                            $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
                        },
                    });
                    
    var panelSuccess = $('.panel-4').scotchPanel({
                        containerSelector: '.panel-3',
                        direction: 'right',
                        duration: 300,
                        transition: 'ease',
                        distanceX: '100%',
                        enableEscapeKey: false,
                        beforePanelOpen: function() {
                            var height = $('.panel-4 .scotch-panel-wrapper:first').height();
                            $('.panel-3 .scotch-panel-wrapper:first').animate({height:height}, 300);
                            $('.panel-2 .scotch-panel-wrapper:first').animate({height:height}, 300);
                            $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
                        }
                    });
    
    $(".validate-db").click(function(event) {
        var validate = $('form').validate();
        
        if (validate.element('#DB_HOST') === true
            && validate.element('#DB_NAME') === true
            && validate.element('#DB_USER') === true
            && validate.element('#DB_PASS') === true
            && validate.element('#DB_CHARSET') === true
            && validate.element('#TABLE_PREFIX') === true){
            panelSite.open();
        }
        else {
            $('.off-canvas').addClass('shake');
            $('.off-canvas').one('webkitAnimationEnd oanimationend msAnimationEnd animationend',
                function () {
                    $('.off-canvas').removeClass('shake');
            });
        }
    });
    
    $(".submit").click(function(event) {
        if ($('form').valid()) {
            var btn = $(this)
            btn.addClass('disabled loading');
            $.ajax({
                url: $(location).attr('href'),
                type: 'post',
                dataType: 'json',
                data: $('form').serialize(),
                success: function(data) {
                            btn.removeClass('loading');
                            $('p.form-control-static.admin_email').html(data.admin_email);
                            $('p.form-control-static.admin_pwd').html(data.admin_pwd);
                            panelSuccess.open();
                        },
                error: function () {
                            $('form').submit();
                        }
            });
        }
        else {
            $('.off-canvas').addClass('shake');
            $('.off-canvas').one('webkitAnimationEnd oanimationend msAnimationEnd animationend',
                function () {
                    $('.off-canvas').removeClass('shake');
            });
        }
    });
    
    if ($( ".alert-danger" ).length > 0) {
        panelDB.open();
    }
});

$("#show-adv-db").click(function() {
    $(this).hide();
    $(".db-adv").removeClass('hidden').addClass('fadeIn');
    var height = $('.panel-2 .scotch-panel-wrapper:first').height();
    $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
});

$("#show-adv-site").click(function() {
    $(this).hide();
    $(".site-adv").removeClass('hidden').addClass('fadeIn');
    var height = $('.panel-3 .scotch-panel-wrapper:first').height();
    $('.panel-2 .scotch-panel-wrapper:first').animate({height:height}, 300);
    $('.panel-1 .scotch-panel-wrapper:first').animate({height:height}, 300);
});

jQuery.validator.setDefaults({
    highlight: function (element, errorClass, validClass) {
        if (element.type === "radio") {
            this.findByName(element.name).addClass(errorClass).removeClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-success has-feedback').addClass('has-error has-feedback');
            $(element).closest('.form-group').find('i.fa').remove();
            $(element).closest('.form-group').append('<i class="fa fa-exclamation form-control-feedback"></i>');
        }
    },
    unhighlight: function (element, errorClass, validClass) {
        if (element.type === "radio") {
            this.findByName(element.name).removeClass(errorClass).addClass(validClass);
        } else {
            $(element).closest('.form-group').removeClass('has-error has-feedback').addClass('has-success has-feedback');
            $(element).closest('.form-group').find('i.fa').remove();
            $(element).closest('.form-group').append('<i class="fa fa-check form-control-feedback"></i>');
        }
    },
    errorPlacement: function () {}
});

$('select').selectize();

$('.list-requirements li').each(function(i){
    var l = $(this);
    var color = $(this).data('color');
    var result = $(this).data('result');
    setTimeout(function(){
        //l.show().addClass('animated fadeInRight');
        l.find('.check').html('<small><span class="animated bounceIn fa-stack text-' + color +'"><i class="fa fa-circle-o fa-stack-2x"></i><i class="fa fa-' + result + ' fa-stack-1x"></i></span></small>');
    }, (i+1) * 200);
});