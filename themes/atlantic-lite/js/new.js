// selectize for category and location selects
$(function(){

    // create 1st category select
    category_select = createCategorySelect();
    // remove d-none class
    $('#category-chained .select-category[data-level="0"]').parent('div').removeClass('d-none');

    // load options for 1st category select
    category_select.load(function(callback) {
        $.ajax({
            url: $('#category-chained').data('apiurl'),
            type: 'GET',
            data: {
                "id_category_parent": 1,
                "sort": 'order',
            },
            success: function(results) {
                callback(results.categories);
            },
            error: function() {
                callback();
            }
        });
    });

    // advertisement location is enabled?
    if ($('#location-chained').length ) {

        // create 1st location select
        location_select = createLocationSelect();
        // remove d-none class
        $('#location-chained .select-location[data-level="0"]').parent('div').removeClass('d-none');

        // load options for 1st location select
        location_select.load(function(callback) {
            $.ajax({
                url: $('#location-chained').data('apiurl'),
                type: 'GET',
                data: {
                    "id_location_parent": 1,
                    "sort": 'order',
                },
                success: function(results) {
                    callback(results.locations);
                    if (results.locations.length === 0)
                        $('#location-chained').closest('.form-group').remove();
                },
                error: function() {
                    callback();
                }
            });
        });
    }
});

function createCategorySelect () {

    // count how many category selects we have rendered
    num_category_select = $('#category-chained .select-category[data-level]').length;

    // clone category select from template
    $('#select-category-template').clone().attr('id', '').insertBefore($('#select-category-template')).find('select').attr('data-level', num_category_select);

    // initialize selectize on created category select
    category_select = $('.select-category[data-level="'+ num_category_select +'"]').selectize({
        valueField:  'id_category',
        labelField:  'translate_name',
        searchField: 'translate_name',
        onChange: function (value) {

            if (!value.length) return;

            // get current category level
            current_level = $('#category-chained .option[data-value="'+ value +'"]').closest('.selectize-control').prev().data('level');

            // is allowed to post on selected category?
            if ( current_level > 0 || (current_level == 0 && $('#category-chained').is('[data-isparent]')))
            {
                // update #category-selected input value
                $('#category-selected').attr('value', value);

                //get category price
                $.ajax({
                    url: $('#category-chained').data('apiurl') + '/' + value,
                    success: function(results) {
                        if (decodeHtml(results.category.price) != $('#category-chained').data('price0')) {
                            price_txt = $('#paid-category .help-block').data('title').replace(/%s/g, results.category.translate_name).replace(/%d/g, decodeHtml(results.category.price));
                            $('#paid-category').removeClass('d-none').find('.help-block span').text(price_txt);
                        }
                        else {
                            $('#paid-category').addClass('d-none');
                        }
                    }
                });
            }
            else
            {
                // set empty value
                $('#category-selected').attr('value', '');
                $('#paid-category').addClass('d-none');
            }

            // get current category level
            current_level = $('#category-chained .option[data-value="'+ value +'"]').closest('.selectize-control').prev().data('level');

            destroyCategoryChildSelect(current_level);

            // create category select
            category_select = createCategorySelect();

            // load options for category select
            category_select.load(function (callback) {
                $.ajax({
                    url: $('#category-chained').data('apiurl'),
                    data: {
                        "id_category_parent": value,
                        "sort": 'order',
                    },
                    type: 'GET',
                    success: function (results) {
                        if (results.categories.length > 0)
                        {
                            callback(results.categories);
                            $('#category-chained .select-category[data-level="' + (current_level + 1) + '"]').parent('div').removeClass('d-none');
                        }
                        else
                        {
                            destroyCategoryChildSelect(current_level);
                        }
                    },
                    error: function () {
                        callback();
                    }
                });
            });
        }
    });

    // return selectize control
    return category_select[0].selectize;
}

function createLocationSelect () {

    // count how many location selects we have rendered
    num_location_select = $('#location-chained .select-location[data-level]').length;

    // clone location select from template
    $('#select-location-template').clone().attr('id', '').insertBefore($('#select-location-template')).find('select').attr('data-level', num_location_select);

    // initialize selectize on created location select
    location_select = $('.select-location[data-level="'+ num_location_select +'"]').selectize({
        valueField:  'id_location',
        labelField:  'translate_name',
        searchField: 'translate_name',
        onChange: function (value) {

            if (!value.length) return;

            // update #location-selected input value
            $('#location-selected').attr('value', value);

            // get current location level
            current_level = $('#location-chained .option[data-value="'+ value +'"]').closest('.selectize-control').prev().data('level');

            destroyLocationChildSelect(current_level);

            // create location select
            location_select = createLocationSelect();

            // load options for location select
            location_select.load(function (callback) {
                $.ajax({
                    url: $('#location-chained').data('apiurl'),
                    data: {
                        "id_location_parent": value,
                        "sort": 'order',
                    },
                    type: 'GET',
                    success: function (results) {
                        if (results.locations.length > 0)
                        {
                            callback(results.locations);
                            $('#location-chained .select-location[data-level="' + (current_level + 1) + '"]').parent('div').removeClass('d-none');
                        }
                        else
                        {
                            destroyLocationChildSelect(current_level);
                        }
                    },
                    error: function () {
                        callback();
                    }
                });
            });
        }
    });

    // return selectize control
    return location_select[0].selectize;
}

function destroyCategoryChildSelect (level) {
    if (level === undefined) return;
    $('#category-chained .select-category[data-level]').each(function () {
        if ($(this).data('level') > level) {
            $(this).parent('div').remove();
        }
    });
}

function destroyLocationChildSelect (level) {
    if (level === undefined) return;
    $('#location-chained .select-location[data-level]').each(function () {
        if ($(this).data('level') > level) {
            $(this).parent('div').remove();
        }
    });
}

$('#category-edit button').click(function(){
    $('#category-chained').removeClass('d-none');
    $('#category-edit').addClass('d-none');
});

$('#location-edit button').click(function(){
    $('#location-chained').removeClass('d-none');
    $('#location-edit').addClass('d-none');
});

// sceditor
$('textarea[name=description]:not(.disable-bbcode)').sceditor({
    format: 'bbcode',
    plugins: "bbcode,plaintext",
    toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
    "bulletlist,orderedlist|link,unlink,youtube|source",
    resizeEnabled: "true",
    emoticonsEnabled: false,
    width: '100%',
    rtl: $('meta[name="application-name"]').data('rtl'),
    style: $('meta[name="application-name"]').data('baseurl') + "themes/default/css/jquery.sceditor.default.min.css",
});

$('textarea[name=description]').prop('required',true);

function initLocationsGMap() {
    jQuery.ajax({
        url: ("https:" == document.location.protocol ? "https:" : "http:") + "//cdn.jsdelivr.net/gmaps/0.4.25/gmaps.min.js",
        dataType: "script",
        cache: true
    }).done(function() {
        locationsGMap();
    });
}

function locationsGMap() {
    // google map set marker on address
    if ($('#map').length !== 0){
        new GMaps({
            div: '#map',
            zoom: parseInt($('#map').attr('data-zoom')),
            lat: $('#map').attr('data-lat'),
            lng: $('#map').attr('data-lon')
        });
        var typingTimer;                //timer identifier
        var doneTypingInterval = 500;  //time in ms, 5 second for example
        //on keyup, start the countdown
        $('#address').keyup(function () {
            clearTimeout(typingTimer);
            if ($(this).val()) {
               typingTimer = setTimeout(doneTyping, doneTypingInterval);
            }
        });
        //user is "finished typing," refresh map
        function doneTyping () {
            GMaps.geocode({
                address: $('#address').val(),
                callback: function (results, status) {
                    if (status == 'OK') {
                        var latlng = results[0].geometry.location;
                        map = new GMaps({
                            div: '#map',
                            lat: latlng.lat(),
                            lng: latlng.lng(),
                        });
                        map.setCenter(latlng.lat(), latlng.lng());
                        map.addMarker({
                            lat: latlng.lat(),
                            lng: latlng.lng(),
                            draggable: true,
                            dragend: function(event) {
                                var lat = event.latLng.lat();
                                var lng = event.latLng.lng();
                                GMaps.geocode({
                                    lat: lat,
                                    lng: lng,
                                    callback: function(results, status) {
                                        if (status == 'OK') {
                                            $("input[name='address']").val(results[0].formatted_address)
                                        }
                                    }
                                });
                                $('#publish-latitude').val(lat).removeAttr("disabled");
                                $('#publish-longitude').val(lng).removeAttr("disabled");
                            },
                        });
                        $('#publish-latitude').val(latlng.lat()).removeAttr("disabled");
                        $('#publish-longitude').val(latlng.lng()).removeAttr("disabled");
                    }
                }
            });
        }
    }

    // auto locate user
    $('.locateme').click(function() {
        var lat;
        var lng;
        GMaps.geolocate({
            success: function(position) {
                lat = position.coords.latitude;
                lng = position.coords.longitude
                map = new GMaps({
                    div: '#map',
                    lat: lat,
                    lng: lng,
                });
                map.setCenter(lat, lng);
                map.addMarker({
                    lat: lat,
                    lng: lng,
                });
                $('#publish-latitude').val(lat).removeAttr("disabled");
                $('#publish-longitude').val(lng).removeAttr("disabled");
                GMaps.geocode({
                    lat: lat,
                    lng: lng,
                    callback: function(results, status) {
                        if (status == 'OK') {
                            $("input[name='address']").val(results[0].formatted_address)
                        }
                    }
                });
            },
            error: function(error) {
                alert('Geolocation failed: '+error.message);
            },
            not_supported: function() {
                alert("Your browser does not support geolocation");
            },
        });
    });
}

// Dropzone

Dropzone.options.imagesDropzone = {
    url: $('#publish-new').attr('action'),
    timeout: 180000,
    autoProcessQueue: false,
    uploadMultiple: true,
    acceptedFiles: 'image/*',
    addRemoveLinks: true,
    resizeMimeType: 'image/jpeg',
    createImageThumbnails: true,
    maxFilesize: $('.images').data('max-image-size'),
    maxFiles: $('.images').data('max-files'),
    parallelUploads: $('.images').data('max-files'),
    parallelUploads: $('.images').data('max-files'),
    resizeWidth: getResizeValue($('.images').data('image-width')),

    init: function () {
        dzClosure = this;

        document.getElementById("publish-new-btn").addEventListener("click", function (e) {
            if (dzClosure.getQueuedFiles().length > 0) {
                e.preventDefault();
                e.stopPropagation();
                //Update the original textarea before validating
                if ($('textarea[name=description]:not(.disable-bbcode)').length) {
                    $('textarea[name=description]:not(.disable-bbcode)').sceditor('instance').updateOriginal();
                }

                if ($('#publish-new').valid()) {
                    $('#processing-modal').on('shown.bs.modal', function () {
                        dzClosure.options.maxFiles++;
                        dzClosure.options.parallelUploads++;

                        // Get the queued files
                        var files = dzClosure.getQueuedFiles();

                        // Sort theme based on the DOM element index
                        files.sort(function (a, b) {
                            return ($(a.previewElement).index() > $(b.previewElement).index()) ? 1 : -1;
                        })

                        // Clear the dropzone queue
                        dzClosure.removeAllFiles();

                        // Add the reordered files to the queue
                        dzClosure.handleFiles(files);
                        dzClosure.processQueue();
                    });

                    if ($('#publish-new').find('.g-recaptcha').length) {
                        var response = grecaptcha.getResponse();
                        if (!response) {
                            $('#publish-new').attr('data-submit-please', 'true');
                            grecaptcha.execute();
                        } else {
                            $('#publish-new').find('input[name="g-recaptcha-response"]').val(response);
                        }
                    } else {
                        $('#processing-modal').modal('show');
                    }
                }
            }
        });

        this.on("sendingmultiple", function (file, xhr, formData) {
            var data = $('#publish-new').serializeArray();
            $.each(data, function (key, el) {
                formData.append(el.name, el.value);
            });
            formData.append('ajax', true);
        });

        this.on("thumbnail", function (file, dataUrl) {
            window.loadImage.parseMetaData(file, function (data) {
                if (data.exif) {
                    var rotation = 1;
                    var rotate = {
                        1: 'rotate(0deg)',
                        2: 'rotate(0deg)',
                        3: 'rotate(180deg)',
                        4: 'rotate(0deg)',
                        5: 'rotate(0deg)',
                        6: 'rotate(90deg)',
                        7: 'rotate(0deg)',
                        8: 'rotate(270deg)'
                    };
                    rotation = data.exif.get('Orientation');

                    $(file.previewElement).find('img').css('transform', rotate[rotation]);
                    // Safari fix
                    $(file.previewElement).find('img').css('-webkit-transform', rotate[rotation]);
                }
            });
        });

        this.on("error", function (file, response) {
            if (response.redirect_url) {
                window.location = response.redirect_url;
            }
        });
    },

    successmultiple: function (file, response) {
        //console.log(response);
        window.location = response.redirect_url;
    }
}

$("#images-dropzone").sortable({
    items: '.dz-preview',
    cursor: 'move',
    opacity: 0.5,
    containment: "parent",
    distance: 20,
    tolerance: 'pointer'
});

// VALIDATION with chosen fix
$(function(){
    $.validator.addMethod(
        "regex",
        function(value, element, regexp) {
            var re = new RegExp(regexp);
            return this.optional(element) || re.test(value);
        }
    );

    // some extra rules for custom fields
    if ($('.cf_decimal_fields').length !== 0)
        var $decimal = $(".cf_decimal_fields").attr("name");
    if ($('.cf_integer_fields').length !== 0)
        var $integer = $(".cf_integer_fields").attr("name");

    var $params = {
        rules:{},
        messages:{},
        focusInvalid: false,
        onkeyup: false,
        errorPlacement: function(error, element) {
            if(element.is(':radio') || element.is(':checkbox')){
                error.insertBefore(element.closest('label'));
            } else if (element.is('textarea')) {
                error.insertAfter(element.closest('textarea'));
            } else if (element.is('select')) {
                error.insertAfter(element.closest('select'));
            } else {
                error.insertAfter(element.closest('input'));
            }
        },
        submitHandler: function(form) {
            $('#processing-modal').on('shown.bs.modal', function () {
                form.submit()
            });

            if ($(form).find('.g-recaptcha').length) {
                var response = grecaptcha.getResponse();
                if (!response) {
                    $(form).attr('data-submit-please', 'true');
                    grecaptcha.execute();
                } else {
                    $(form).find('input[name="g-recaptcha-response"]').val(response);
                }
            } else {
                $('#processing-modal').modal('show');
            }
        },
        invalidHandler: function(form, validator) {
            if (!validator.numberOfInvalids())
                return;
            $('html, body').animate({
                scrollTop: $(validator.errorList[0].element).offset().top
            }, 500);
        }
    };
    $params['rules'][$integer] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['rules'][$decimal] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['rules']['price'] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,8})?$"};
    $params['rules']['title'] = {maxlength: 145};
    $params['rules']['address'] = {maxlength: 145};
    $params['rules']['phone'] = {maxlength: 30};
    $params['rules']['website'] = {maxlength: 200};
    $params['rules']['captcha'] =   {
                                        "remote" :
                                        {
                                            url: $(".post_new").attr('action'),
                                            type: "post",
                                            data:
                                            {
                                                ajaxValidateCaptcha: true
                                            }
                                        }
                                    };

    $params['rules']['hidden-recaptcha'] = {
        required: function () {
            if (grecaptcha.getResponse() == '') {
                return true;
            } else {
                return false;
            }
        }
    }
    $params['rules']['email'] = {emaildomain: $('.post_new :input[name="email"]').data('domain')};
    $params['rules']['description'] = {nobannedwords: $('.post_new :input[name="description"]').data('bannedwords')};
    $params['messages']['price'] = {"regex" : $('.post_new :input[name="price"]').data('error')};
    $params['messages']['captcha'] = {"remote" : $('.post_new :input[name="captcha"]').data('error')};
    $params['messages']['email'] = {"emaildomain" : $('.post_new :input[name="email"]').data('error')};
    $params['messages']['description'] = {"nobannedwords" : $('.post_new :input[name="description"]').data('error')};

    $.validator.setDefaults({ ignore: ":hidden:not(select, .hidden-recaptcha)" });
    var $form = $(".post_new");
    $form.validate($params);

    //chosen fix
    var settings = $.data($form[0], 'validator').settings;
    settings.ignore += ':not(#location)'; // post_new location(any chosen) texarea
    settings.ignore += ':not([name="description"])'; // post_new description texarea
});

// sure you want to leave alert and processing modal
$(function(){
    if ($('input[name=leave_alert]').length === 0 && typeof ouibounce == 'function') {
        var _ouibounce = ouibounce(false, {
            aggressive: true,
            callback: function() {
                swal({
                    title: $('#publish-new-btn').data('swaltitle'),
                    text: $('#publish-new-btn').data('swaltext'),
                    type: "warning",
                    allowOutsideClick: true
                });
            }
        });
    }
});

$("#price").keyup(function() {
    if ($(this).data('decimal_point') == ',')
        $(this).val($(this).val().replace(/[^\d,]/g, ''));
    else
        $(this).val($(this).val().replace(/[^\d.]/g, ''));
});


if ($('#phone').length) {
    $("#phone").intlTelInput({
        formatOnDisplay: false,
        autoPlaceholder: false,
        initialCountry: $('#phone').data('country')
    });
}
