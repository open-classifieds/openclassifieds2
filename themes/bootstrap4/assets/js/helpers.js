window.setCookie = function(c_name,value,exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays==null) ? "" : ";path=/; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
};

window.readCookie = function(name) {
    var value = "; " + document.cookie;
    var parts = value.split("; " + name + "=");
    if (parts.length == 2) return parts.pop().split(";").shift();
};

window.initAutoLocate = function() {
    if ($('input[name="auto_locate"]').length) {
        jQuery.ajax({
            url: ("https:" == document.location.protocol ? "https:" : "http:") + "//cdn.jsdelivr.net/gmaps/0.4.25/gmaps.min.js",
            dataType: "script",
            cache: true
        }).done(function() {
            autoLocate();
        });
    }
};

window.autoLocate = function() {
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
                    $('#auto-locations .list-group-item').on('click', function(event) {
                        event.preventDefault();
                        $this = $(this);
                        $.post($('meta[name="application-name"]').data('baseurl'), {
                            user_location: $this.data('id')
                        })
                        .done(function( data ) {
                            window.location.href = $this.attr('href');
                        });
                    });
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
};

window.createCookie = function(name, value, seconds) {
    if (seconds) {
        var date = new Date();
        date.setTime(date.getTime()+(seconds*1000));
        var expires = "; expires="+date.toGMTString();
    }
    else var expires = "";
    document.cookie = name+"="+value+expires+"; path=/";
};

window.readCookie = function(name) {
    var nameEQ = encodeURIComponent(name) + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return decodeURIComponent(c.substring(nameEQ.length, c.length));
    }
    return null;
}

window.eraseCookie = function(name) {
    createCookie(name, "", -1);
}

window.decodeHtml = function(html) {
    var txt = document.createElement("textarea");
    txt.innerHTML = html;
    return txt.value;
}
