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
                    $('#location-chained').closest('.form-group').hide();
            },
            error: function() {
                callback();
            }
        });
    });
    
    // show custom fields
    if ($('#category-selected').val().length > 0) {
        $.ajax({
            url: $('#category-chained').data('apiurl') + '/' + $('#category-selected').val(),
            success: function(results) {
                createCustomFieldsByCategory(results.category.customfields);
            }
        });
    }
    else {
        $.ajax({
            url: $('#category-chained').data('apiurl') + '/' + 1,
            success: function(results) {
                createCustomFieldsByCategory(results.category.customfields);
            }
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
                        // show custom fields for this category
                        createCustomFieldsByCategory(results.category.customfields);
                    }
                });
            }
            else
            {
                // set empty value
                $('#category-selected').attr('value', '');
                $('#paid-category').addClass('hidden');
                // show custom fields
                $.ajax({
                    url: $('#category-chained').data('apiurl') + '/' + 1,
                    success: function(results) {
                        createCustomFieldsByCategory(results.category.customfields);
                    }
                });
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
    e.preventDefault();
    var text = (e.originalEvent || e).clipboardData.getData('text/plain');
    $(".sceditor-container iframe")[0].contentWindow.document.execCommand('insertText', false, text);
});

//sceditorBBCodePlugin for validation, updates iframe on submit 
$("button[name=submit]").click(function(){
    $("textarea[name=description]").data("sceditor").updateOriginal();
});

// google map set marker on address
if($('#map').length !== 0){
    map = new GMaps({
        div: '#map',
        zoom: parseInt($('#map').attr('data-zoom')),
        lat: $('#map').attr('data-lat'),
        lng: $('#map').attr('data-lon')
    });
    map.setCenter($('#map').attr('data-lat'), $('#map').attr('data-lon'));
    map.addMarker({
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

// validate image size
$('input[name^="image"]').on('change', function() {

    //check whether browser fully supports all File API
    if (FileApiSupported())
    {
        //get the file size and file type from file input field
        var $input = $(this);
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
            
            $(this).replaceWith($(this).val('').clone(true));
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
                        }).appendTo('.edit_ad_form');
                }
            });
        }
    }
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

    var $params = {
        rules:{},
        messages:{},
        submitHandler: function(form) {
            $('#processing-modal').on('shown.bs.modal', function() {
                if (FileApiSupported())
                    $.when(clearFileInput($('input[name="image0"]'))).then(form.submit());
                else
                    form.submit()
            });
            $('#processing-modal').modal('show');
        },
    };
    $params['rules']['price'] = {regex: "^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$"};
    $params['rules']['title'] = {maxlength: 145};
    $params['rules']['address'] = {maxlength: 145};
    $params['rules']['phone'] = {maxlength: 30};
    $params['rules']['website'] = {maxlength: 200};
    $params['messages']['price'] =   {"regex" : $('.edit_ad_form :input[name="price"]').data('error')};

    $.validator.setDefaults({ ignore: ":hidden:not(select)" });
    var $form = $(".edit_ad_form");
    $form.validate($params);

    //chosen fix
    var settings = $.data($form[0], 'validator').settings;
    settings.ignore += ':not(.cf_select_fields)'; // post_new location(any chosen) texarea
    // settings.ignore += ':not(.sceditor-container)'; // post_new description texarea
    settings.ignore += ':not(#description)'; // post_new description texarea
});

function createCustomFieldsByCategory (customfields) {
    $('#custom-fields > div').not("#custom-field-template").remove();
    $.each(customfields, function (idx, customfield) {
        // don't create admin privilege custom fields if user is not moderator or admin
        if (customfield.admin_privilege && $('#custom-fields').data("admin-privilege") === undefined)
            return;
        // clone custom field from template
        var $template = $('#custom-field-template').clone().attr('id', '').removeClass('hidden').appendTo('#custom-fields');
        $template.find('div[data-label]').replaceWith($('<label/>').attr({'for' : idx}).html(customfield.label));
        
        switch (customfield.type) {
            case 'string':
                $template.find('div[data-input]').replaceWith($('<input/>').attr({  'type'        : 'text',
                                                                                    'id'          : idx,
                                                                                    'name'        : idx,
                                                                                    'class'       : 'form-control',
                                                                                    'placeholder' : customfield.label,
                                                                                    'data-type'   : customfield.type,
                                                                                    'data-toggle' : 'tooltip',
                                                                                    'title'       : customfield.tooltip,
                                                                                    'required'    : customfield.required,
                                                                                    'value'       : $('#custom-fields').data('customfield-values')[customfield.label],
                                                                                }));
                break;
            case 'textarea':
                $template.find('div[data-input]').replaceWith($('<textarea/>').attr({   'id'          : idx,
                                                                                        'name'        : idx,
                                                                                        'class'       : 'form-control',
                                                                                        'placeholder' : customfield.label,
                                                                                        'rows'        : 10,
                                                                                        'cols'        : 50,
                                                                                        'data-type'   : customfield.type,
                                                                                        'data-toggle' : 'tooltip',
                                                                                        'title'       : customfield.tooltip,
                                                                                        'required'    : customfield.required,
                                                                                    }).append($('#custom-fields').data('customfield-values')[customfield.label]));
                break;
            case 'integer':
                $template.find('div[data-input]').replaceWith($('<input/>').attr({  'type'        : 'text',
                                                                                    'id'          : idx,
                                                                                    'name'        : idx,
                                                                                    'class'       : 'form-control',
                                                                                    'placeholder' : customfield.label,
                                                                                    'data-type'   : customfield.type,
                                                                                    'data-toggle' : 'tooltip',
                                                                                    'title'       : customfield.tooltip,
                                                                                    'required'    : customfield.required,
                                                                                    'value'       : $('#custom-fields').data('customfield-values')[customfield.label],
                                                                                }));
                $('#custom-fields input[name="' + idx + '"]').rules('add', {
                                                                                regex: '^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$'
                                                                            });
                break;
            case 'decimal':
                $template.find('div[data-input]').replaceWith($('<input/>').attr({  'type'        : 'text',
                                                                                    'id'          : idx,
                                                                                    'name'        : idx,
                                                                                    'class'       : 'form-control',
                                                                                    'placeholder' : customfield.label,
                                                                                    'data-type'   : customfield.type,
                                                                                    'data-toggle' : 'tooltip',
                                                                                    'title'       : customfield.tooltip,
                                                                                    'required'    : customfield.required,
                                                                                    'value'       : $('#custom-fields').data('customfield-values')[customfield.label],
                                                                                }));
                $('#custom-fields input[name="' + idx + '"]').rules('add', {
                                                                                regex: '^[0-9]{1,18}([,.]{1}[0-9]{1,3})?$'
                                                                            });
                break;
            case 'date':
                $template.find('div[data-input]').replaceWith($('<input/>').attr({  'type'             : 'text',
                                                                                    'id'               : idx,
                                                                                    'name'             : idx,
                                                                                    'class'            : 'form-control',
                                                                                    'placeholder'      : customfield.label,
                                                                                    'data-type'        : customfield.type,
                                                                                    'data-toggle'      : 'tooltip',
                                                                                    'title'            : customfield.tooltip,
                                                                                    'data-date-format' : 'yyyy-mm-dd',
                                                                                    'required'         : customfield.required,
                                                                                    'value'       : $('#custom-fields').data('customfield-values')[customfield.label],
                                                                                }));
                $('#custom-fields input[name="' + idx + '"]').datepicker()
                break;
            case 'email':
                $template.find('div[data-input]').replaceWith($('<input/>').attr({  'type'        : 'email',
                                                                                    'id'          : idx,
                                                                                    'name'        : idx,
                                                                                    'class'       : 'form-control',
                                                                                    'placeholder' : customfield.label,
                                                                                    'data-type'   : customfield.type,
                                                                                    'data-toggle' : 'tooltip',
                                                                                    'title'       : customfield.tooltip,
                                                                                    'required'    : customfield.required,
                                                                                    'value'       : $('#custom-fields').data('customfield-values')[customfield.label],
                                                                                }));
                break;
            case 'select':
                $template.find('div[data-input]').replaceWith($('<select/>').attr({ 'id'          : idx,
                                                                                    'name'        : idx,
                                                                                    'class'       : 'form-control',
                                                                                    'placeholder' : customfield.label,
                                                                                    'data-type'   : customfield.type,
                                                                                    'required'    : customfield.required,
                                                                                }));
                $('#custom-fields select[name="' + idx + '"]').append($('<option/>').val(' ').html('&nbsp;'));
                for (var val in customfield.values) {
                    $('#custom-fields select[name="' + idx + '"]').append($('<option/>').val(customfield.values[val]).html(customfield.values[val]));
                }
                $('#custom-fields select[name="' + idx + '"] option[value="' + $('#custom-fields').data('customfield-values')[customfield.label] +'"]').attr('selected', true);
                // selectize allowEmptyOption bugfix
                $('#custom-fields select[name="' + idx + '"]').selectize({
                    allowEmptyOption: 'true',
                    onChange: function(value) {
                        if (value == ' ')
                            $('#custom-fields select[name="' + idx + '"] option[selected]').val(null);
                    }
                });
                $('#custom-fields select[name="' + idx + '"] option[value=" "]').val(null);
                break;
            case 'radio':
                $.each(customfield.values, function (radioidx, value) {
                    $('<div/>').attr('class', 'radio').append($('<label/>').append($('<input/>').attr({ 'type'        : 'radio',
                                                                                                        'id'          : idx,
                                                                                                        'name'        : idx,
                                                                                                        'data-type'   : customfield.type,
                                                                                                        'data-toggle' : 'tooltip',
                                                                                                        'title'       : customfield.tooltip,
                                                                                                        'required'    : customfield.required,
                                                                                                        'value'       : radioidx + 1,
                                                                                                        'checked'     : (value == $('#custom-fields').data('customfield-values')[customfield.label]) ? true:false,
                                                                                                    })).append(value)).insertBefore($template.find('div[data-input]'));
                });
                $template.find('div[data-input]').remove();
                break;
            case 'checkbox':
                $template.find('div[data-input]').wrap('<div class="checkbox"></div>').wrap('<label></label>');
                $template.find('div[data-input]').replaceWith($('<input/>').attr({  'type'        : 'checkbox',
                                                                                    'id'          : idx,
                                                                                    'name'        : idx,
                                                                                    'data-type'   : customfield.type,
                                                                                    'data-toggle' : 'tooltip',
                                                                                    'title'       : customfield.tooltip,
                                                                                    'required'    : customfield.required,
                                                                                    'checked'     : $('#custom-fields').data('customfield-values')[customfield.label],
                                                                                }));
                break;
        }
    });

    $('input[data-toggle=tooltip]').tooltip({
        placement: "right",
        trigger: "focus"
    });
}

$(function(){
    $(".img-delete").click(function(e) {
        var href = $(this).attr('href');
        var title = $(this).data('title');
        var text = $(this).data('text');
        var img_id = $(this).attr('value');
        var confirmButtonText = $(this).data('btnoklabel');
        var cancelButtonText = $(this).data('btncancellabel');
        e.preventDefault();
        swal({
            title: title,
            text: text,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            allowOutsideClick: true,
        },
        function(){
            $('#processing-modal').modal('show');
            $.ajax({
                type: "POST",
                url: href,
                data: {img_delete: img_id},
                cache: false
            }).done(function(result) {
                $('#img' + img_id).toggle('slide');
                $('#processing-modal').modal('hide');
            }).fail(function() {
                $('#processing-modal').modal('hide');
            });
        });
    });

    $(".img-primary").click(function(e) {
        e.preventDefault();
        var href = $(this).attr('href');
        var title = $(this).data('title');
        var text = $(this).data('text');
        var img_id = $(this).attr('value');
        
        $('#processing-modal').modal('show');
        $.ajax({
            type: "POST",
            url: href,
            data: {primary_image: img_id},
            cache: false
        }).done(function(result) {
            $('#processing-modal').modal('hide');
            window.location.href = href;
        }).fail(function() {
            $('#processing-modal').modal('hide');
            window.location.href = href;
        });
    }); 
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
