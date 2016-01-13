$(function(){
    
    //favorites system
	$('.add-favorite, .remove-favorite').click(function(event) {
		  event.preventDefault();
		  $this = $(this);
		  $.ajax({ url: $this.attr('href'),
				}).done(function ( data ) {

                    //favorites counter
                    countname = 'count'+$this.data('id');
                    if(document.getElementById(countname))
                    {
                        currentvalue = parseInt($('#'+countname).html(),10);
                        if($('#'+$this.data('id')+' a').hasClass('add-favorite remove-favorite'))
                            $('#'+countname).html(currentvalue-1);
                        else
                            $('#'+countname).html(currentvalue+1);
                    }
                    
					$('#'+$this.data('id')+' a').toggleClass('add-favorite remove-favorite');
					$('#'+$this.data('id')+' a i').toggleClass('glyphicon-heart-empty glyphicon-heart');
				});
	});

});

$(function(){

    //notification system
    var favicon = new Favico({
        animation : 'popFade'
    });

    $('#contact-notification').click(function(event) {
        $.get($(this).data('url'));
        $(document).mouseup(function (e)
        {
            var contact = $("#contact-notification");
        
            if (!contact.is(e.target) // if the target of the click isn't the container...
                && contact.has(e.target).length === 0) // ... nor a descendant of the container
            {
                //$("#contact-notification").slideUp();
                $("#contact-notification span").hide();
                $("#contact-notification i").removeClass('fa-bell').addClass('fa-bell-o');
                $("#contact-notification-dd" ).remove();
                favicon.badge(0);
            }
        });
    });
    
    //intial value
    favicon.badge($('#contact-notification span').text());
});

//validate auth pages
$(function(){
    
    $.validator.addMethod(
        "emaildomain",
        function(value, element, domains) {
            if (domains.length === 0)
                return true;

            for (var i = 0; i < domains.length; i++) {
                if (value.indexOf(("@" + domains[i]), value.length - ("@" + domains[i]).length) !== -1) {
                    return true;
                }
            }

            return false;
        }
    );

    $.validator.addMethod(
        "nobannedwords",
        function(value, element, words) {
            if (words.length === 0)
                return true;

            for (var i = 0; i < words.length; i++) {
                if (value.indexOf(words[i]) !== -1) {
                    return false;
                }
            }

            return true;
        }
    );

    var $params = {rules:{}, messages:{}};
    $params['rules']['email'] = {required: true, email: true};

    $(".auth").each(function() {
        $(this).validate($params)
    });

    var $register_params = {rules:{}, messages:{}};
    $register_params['rules']['email'] = {required: true, email: true, emaildomain: $('.register :input[name="email"]').data('domain')};
    $register_params['rules']['password1'] = {required: true};
    $register_params['rules']['password2'] = {required: true};
    $register_params['messages']['email'] = {"emaildomain" : $('.register :input[name="email"]').data('error')};

    $(".register").each(function() {
        $(this).validate($register_params)
    });
    
});

function createCookie(name,value,seconds) {
    if (seconds) {
        var date = new Date();
        date.setTime(date.getTime()+(seconds*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

function eraseCookie(name) {
    createCookie(name, "", -1);
}

function decodeHtml(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}

$(function(){
    if ($('input[name="auto_locate"]').length) {

        $('#auto-locations').on('show.bs.modal', function () {
            $('.modal .modal-body').css('overflow-y', 'auto'); 
            $('.modal .modal-body').css('max-height', $(window).height() * 0.8);
        });

        $('#auto-locations').modal('show');

        if ( ! readCookie('cancel_auto_locate') && ( ! readCookie('mylat') || ! readCookie('mylng'))) {
            var lat;
            var lng;
            GMaps.geolocate({
                success: function(position) {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude
                    // 30 minutes cookie
                    createCookie('mylat',lat,1800);
                    createCookie('mylng',lng,1800);
                    // show modal
                    $.get($('meta[name="application-name"]').data('baseurl'), function(data) {
                        $('input[name="auto_locate"]').after($(data).find("#auto-locations"));
                        $('#auto-locations').modal('show');
                    })
                },
                error: function(error) {
                    console.log('Geolocation failed: '+error.message);
                    createCookie('cancel_auto_locate',1,1800);
                },
                not_supported: function() {
                    console.log("Your browser does not support geolocation");
                    createCookie('cancel_auto_locate',1,1800);
                },
            });
        }
    }

    $('#auto-locations .list-group-item').click(function(event) {
        event.preventDefault();
        $this = $(this);
        $.post($('meta[name="application-name"]').data('baseurl'), {
            user_location: $this.data('id')
        })
        .done(function( data ) {
            window.location.href = $this.attr('href');
        });
    });

    $('#auto-locations .close').click( function(){
        createCookie('cancel_auto_locate',1,1800);
    });

    setInterval(function () {
        if ( ! navigator.onLine )
            $('.off-line').show();
        else
            $('.off-line').hide();
    }, 250);
});

$(function(){
    // Check for LocalStorage support.
    if (localStorage && $('#Widget_RecentlySearched')) {
        $('.Widget_RecentlySearched').hide();
        var recentSearches = [];

        if (localStorage["recentSearches"]) {
            $('.Widget_RecentlySearched').show();
            recentSearches = JSON.parse(localStorage['recentSearches']);

            var list = $('ul#Widget_RecentlySearched')

            $.each(recentSearches, function(i) {

                values = JSON.parse(this);
                var text = '';

                $.each(values, function(j) {
                    if (jQuery.type(this) === 'string' && this != '' && this != values.serialize)
                        text = text + this + ' - ';
                })

                text = text.slice(0,-3)

                var li = $('<li/>')
                    .appendTo(list);
                var a = $('<a/>')
                    .attr('href', $('#Widget_RecentlySearched').data('url') + '?' + values.serialize)
                    .text(text)
                    .appendTo(li);
            })
        }

        form = 'form[action*="' + $('#Widget_RecentlySearched').data('url') + '"]';

        // Add an event listener for form submissions
        $(form).on('submit', function() {

            var $inputs = $(this).find(':input:not(:button):not(:checkbox):not(:radio)');
            var values = {};

            $inputs.each(function() {
                if (this.name) {
                    values[this.name] = $(this).val();
                }
            });

            values['serialize'] = $(this).serialize();

            values = JSON.stringify(values);

            recentSearches.unshift(values);
            if (recentSearches.length > $('#Widget_RecentlySearched').data('max-items')) { 
                recentSearches.pop();
            }

            localStorage['recentSearches'] = JSON.stringify(recentSearches);
        });

    }
});
