// selectize for category and location selects
$(function(){
    
    // create 1st category select
    category_select = createCategorySelect();
    // remove hidden class
    $('#category-chained .select-category[data-level="0"]').parent('div').removeClass('hidden');
    
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
        // remove hidden class
        $('#location-chained .select-location[data-level="0"]').parent('div').removeClass('hidden');
        
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
        labelField:  'name',
        searchField: 'name',
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
                            price_txt = $('#paid-category .help-block').data('title').replace(/%s/g, results.category.name).replace(/%d/g, results.category.price);
                            $('#paid-category').removeClass('hidden').find('.help-block span').text(price_txt);
                        }
                        else {
                            $('#paid-category').addClass('hidden');
                        }
                    }
                });
            }
            else
            {
                // set empty value
                $('#category-selected').attr('value', '');
                $('#paid-category').addClass('hidden');
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
                            $('#category-chained .select-category[data-level="' + (current_level + 1) + '"]').parent('div').removeClass('hidden');
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
        labelField:  'name',
        searchField: 'name',
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
                            $('#location-chained .select-location[data-level="' + (current_level + 1) + '"]').parent('div').removeClass('hidden');
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
    $('#category-chained').removeClass('hidden');
    $('#category-edit').addClass('hidden');
});
    
$('#location-edit button').click(function(){
    $('#location-chained').removeClass('hidden');
    $('#location-edit').addClass('hidden');
});

// sceditor
$('textarea[name=description]:not(.disable-bbcode)').sceditorBBCodePlugin({
    toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
    "bulletlist,orderedlist|link,unlink,youtube|source",
    resizeEnabled: "true",
    emoticonsEnabled: false,
    style: $('meta[name="application-name"]').data('baseurl') + "themes/default/css/jquery.sceditor.default.min.css",
});
	
// paste plain text in sceditor
$(".sceditor-container iframe").contents().find("body").bind('paste', function(e) {
    var text = ''; var that = $(this);

    if (e.clipboardData)
        text = e.clipboardData.getData('text/plain');
    else if (window.clipboardData)
        text = window.clipboardData.getData('Text');
    else if (e.originalEvent.clipboardData)
        text = $('<div></div>').text(e.originalEvent.clipboardData.getData('text'));

        
    if (document.queryCommandSupported('insertText')) {
        $(".sceditor-container iframe")[0].contentWindow.document.execCommand('insertHTML', false, $(text).html());
        return false;
    }
    else { // IE > 7
        that.find('*').each(function () {
             $(this).addClass('within');
        });

        setTimeout(function () {
            that.find('*').each(function () {
                $(this).not('.within').contents().unwrap();
            });
        }, 1);
    }
});	

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

$('.fileinput').on('change.bs.fileinput', function() {

    //check whether browser fully supports all File API
    if (FileApiSupported())
    {
        //get the file size and file type from file input field
        var $input = $(this).find('input[name^="image"]');
        var image = $input[0].files[0];
        var max_size = $('.images').data('max-image-size')*1048576 // max size in bites

        if (image && image.size > max_size)
        {
            swal({
                title: '',
                text: $('.images').data('swaltext'),
                type: "warning",
                allowOutsideClick: true
            });
            
            $(this).closest('.fileinput').fileinput('clear');
        }
        else
        {
            //resize image
            canvasResize(image, {
                width: $('.images').data('image-width'),
                height: $('.images').data('image-height'),
                crop: false,
                quality: $('.images').data('image-quality'),
                callback: function(data, width, height) {
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'base64_' + $input.attr('name'),
                        value: data
                        }).appendTo('#publish-new');
                }
            });
        }
    }

    //unhide next box image after selecting first
    $(this).next('.fileinput').removeClass('hidden');
});

$('.fileinput').on('clear.bs.fileinput', function() {
    var $input = $(this).find('input[name^="image"]');
    $('input[name="base64_' + $input.attr('name') + '"]').remove();
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
        submitHandler: function(form) {
            $('#processing-modal').on('shown.bs.modal', function() {
                if (FileApiSupported())
                    $.when(clearFileInput($('input[name^="image"]'))).then(form.submit());
                else
                    form.submit()
            });
            $('#processing-modal').modal('show');
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
    $params['rules']['price'] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
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
    $params['rules']['email'] = {emaildomain: $('.post_new :input[name="email"]').data('domain')};
    $params['rules']['description'] = {nobannedwords: $('.post_new :input[name="description"]').data('bannedwords')};
    $params['messages']['price'] = {"regex" : $('.post_new :input[name="price"]').data('error')};
    $params['messages']['captcha'] = {"remote" : $('.post_new :input[name="captcha"]').data('error')};
    $params['messages']['email'] = {"emaildomain" : $('.post_new :input[name="email"]').data('error')};
    $params['messages']['description'] = {"nobannedwords" : $('.post_new :input[name="description"]').data('error')};

    $.validator.setDefaults({ ignore: ":hidden:not(select)" });
    var $form = $(".post_new");
    $form.validate($params);

    //chosen fix
    var settings = $.data($form[0], 'validator').settings;
    settings.ignore += ':not(#location)'; // post_new location(any chosen) texarea
    settings.ignore += ':not([name="description"])'; // post_new description texarea
});

// sure you want to leave alert and processing modal
$(function(){
    if ($('input[name=leave_alert]').length === 0) {
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

function clearFileInput($input) {
    if ($input.val() == '') {
        return;
    }
    // Fix for IE ver < 11, that does not clear file inputs
    if (/MSIE/.test(navigator.userAgent)) {
        var $frm1 = $input.closest('form');
        if ($frm1.length) {
            $input.wrap('<form>');
            var $frm2 = $input.closest('form'),
                $tmpEl = $(document.createElement('div'));
            $frm2.before($tmpEl).after($frm1).trigger('reset');
            $input.unwrap().appendTo($tmpEl).unwrap();
        } else {
            $input.wrap('<form>').closest('form').trigger('reset').unwrap();
        }
    } else if (/iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream) {
        $input.replaceWith($input.clone());
    } else {
        $input.val('');
    }
}

// check whether browser fully supports all File API
function FileApiSupported() {
    if (window.File && window.FileReader && window.FileList && window.Blob)
        return true;

    return false;
}

$("#price").keyup(function() {
    $(this).val($(this).val().replace(/[^\d.,]/g, ''));
});
