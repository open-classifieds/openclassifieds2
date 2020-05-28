function init_panel()
{
    if ($("textarea[name=description], textarea[name='formorm[description]']").data('editor')=='html')
    {
        $("#formorm_description, textarea[name=description], textarea[name=email_purchase_notes], .cf_textarea_fields").summernote({
            height: "450",
            placeholder: ' ',
            toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video', 'hr']],
                        ['view', ['fullscreen', 'codeview']],
                        ['help', ['help']],
            ],
            callbacks: {
                onInit: function() {
                    $(".note-placeholder").text($(this).attr('placeholder'));
                },
                onPaste: function (e) {
                    var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                    e.preventDefault();
                        document.execCommand('insertText', false, text);
                },
                onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            }
        });
    }
	else if ($( "#crud-post" ).length || $( "#crud-category" ).length || $( "#crud-location" ).length) {
        $("#formorm_description, textarea[id^=translations_description]").summernote({
            height: "350",
            placeholder: ' ',
            toolbar: [
                        ['style', ['style']],
                        ['font', ['bold', 'italic', 'underline', 'clear']],
                        ['fontname', ['fontname']],
                        ['fontsize', ['fontsize']],
                        ['color', ['color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['height', ['height']],
                        ['table', ['table']],
                        ['insert', ['link', 'picture', 'video', 'hr']],
                        ['view', ['fullscreen', 'codeview']],
                        ['help', ['help']],
            ],
            callbacks: {
                onInit: function() {
                    $(".note-placeholder").text($(this).attr('placeholder'));
                },
                onPaste: function (e) {
                    var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                    e.preventDefault();
                        document.execCommand('insertText', false, text);
                },
                onImageUpload: function(files, editor, welEditable) {
                    sendFile(files[0], editor, welEditable);
                }
            }
        });
	}
    else
    {
        $('#formorm_description, textarea[name=description]:not(.disable-bbcode), textarea[name=email_purchase_notes], .cf_textarea_fields').sceditor({
            format: 'bbcode',
            plugins: "bbcode,plaintext",
            toolbar: "bold,italic,underline,strike,|left,center,right,justify|" +
                "bulletlist,orderedlist|link,unlink,image,youtube|source",
            resizeEnabled: "true",
            emoticonsEnabled: false,
            width: '100%',
            rtl: $('meta[name="application-name"]').data('rtl'),
            style: $('meta[name="application-name"]').data('baseurl') + "themes/default/css/jquery.sceditor.default.min.css",
        });
    }

    // hack to submit form data from summernote source code mode
    $("form").submit(function(e) {
        if ($("textarea[name=description], textarea[name='formorm[description]']").data('editor')=='html')
        {
            if ($('#formorm_description, textarea[name=description], textarea[name=email_purchase_notes], .cf_textarea_fields').summernote('codeview.isActivated')) {
                $('#formorm_description, textarea[name=description], textarea[name=email_purchase_notes], .cf_textarea_fields').summernote('codeview.deactivate');
            }
        }
        else if ($( "#crud-post" ).length || $( "#crud-category" ).length || $( "#crud-location" ).length) {
            if ($('#formorm_description').summernote('codeview.isActivated')) {
                $('#formorm_description').summernote('codeview.deactivate');
            }
        }
    });

    $('.tips').popover();

    initSelect2();

    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');

    // check all checkboxes in a table
    $('#select-all').click(function(e){
        var table= $(e.target).closest('table');
        $('td input:checkbox',table).prop('checked',this.checked);
    });

    $('select[name="locale_select"]').change(function()
    {
         $('#locale_form').submit();
    });
    $('select[name="type"]').change(function()
    {
        // alert($(this).val());
        if($(this).val() == 'email')
            $('#from_email').parent().parent().css('display','block');
        else
            $('#from_email').parent().parent().css('display','none');
    });

    $('input').each(function(){
        if( $(this).attr('type') != 'checkbox' && !$(this).hasClass('form-control')) {$(this).addClass('form-control');} // other than checkbox

        if($(this).attr('type') == 'checkbox' && $(this).hasClass('form-control')) {$(this).removeClass('form-control');}

        if($(this).attr('type') == 'radio')
            $(this).removeClass('form-control');
    });

	// Menu icon picker
	$(".icon-picker").iconPicker();

	// Call open_eshop.init function only if exist
	if (typeof open_eshop !== 'undefined' && $.isFunction(open_eshop.init)) {open_eshop.init(open_eshop);}

	// Modal confirmation
	$('a[data-toggle="confirmation"]').click(function(event) {
	    var href = $(this).attr('href');
	    var title = $(this).attr('title');
	    var text = $(this).data('text');
	    var confirmButtonText = $(this).data('btnoklabel');
	    var cancelButtonText = $(this).data('btncancellabel');
	    event.preventDefault();
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
	        window.open(href,"_self");
	    });
	});

    $('button[data-toggle="confirmation"]').on('click', function(event) {
        event.preventDefault();
        var form = $(this).closest('form');
        var formaction = $(this).attr('formaction');
        var title = $(this).attr('title');
        var text = $(this).data('text');
        var confirmButtonText = $(this).data('btnoklabel');
        var cancelButtonText = $(this).data('btncancellabel');
        event.preventDefault();
        swal({
            title: title,
            text: text,
            type: "info",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: confirmButtonText,
            cancelButtonText: cancelButtonText,
            allowOutsideClick: true,
            closeOnConfirm: true,
        }, function(confirmed) {
            if (confirmed) {
                form.attr('action', formaction);
                form.submit();
            }
        });
    });

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

    //load modal documentation
    $('a[href*="docs.yclas.com"]').click(function( event ) {
        event.preventDefault();
        $('#docModal .modal-body').load($(this).attr('href') + ' .post', function() {
            $('#docModal .modal-body img').each( function() {
                $(this).addClass('img-responsive');
            });
            $('#docModal').modal('show');
        });
    });
}

$(function (){
    init_panel();

    // Search widget in header
    $('.oc-faq-btn').click(function() {
        // event.preventDefault();
        $('.header-oc-faq').toggle();
    });
});


//from https://github.com/peachananr/loading-bar
//I have recoded it a bit since uses a loop each, which is not convenient for me at all
$(function(){
    $("body").on( "click", "a.ajax-load",function(e){
        e.preventDefault();
        $("html,body").scrollTop(0);
        button = $(this);
        //get the link location that was clicked
        pageurl = button.attr('href');
        button.css('cursor','wait');
        //to get the ajax content and display in div with id 'page-wrapper'
        $.ajax({
            url:updateURLParameter(pageurl,'rel','ajax'),
            beforeSend: function() {
                                        if ($("#loadingbar").length === 0) {
                                            $("body").append("<div id='loadingbar'></div>")
                                            $("#loadingbar").addClass("waiting").append($("<dt/><dd/>"));
                                            $("#loadingbar").width((50 + Math.random() * 30) + "%");
                                        }
                                    }
                                    }).always(function() {
                                        $("#loadingbar").width("101%").delay(200).fadeOut(400, function() {
                                        $(this).remove();});
                                    }).done(function(data) {
                                        document.title = button.attr('title');
                                        if ( history.replaceState ) history.pushState( {}, document.title, pageurl );
                                        $('.br').removeClass('active');
                                        button.closest('.br').addClass('active');
                                        button.css('cursor','');
                                        $("#page-wrapper").html(data);
                                        init_panel();
                                    });

        return false;
    });
});

/* the below code is to override back button to get the ajax content without reload*/
$(window).bind('load', function() {
    setTimeout(function() {
        $(window).bind('popstate', function() {
            $.ajax({url:updateURLParameter(location.pathname,'rel','ajax'),success: function(data){
                $('#page-wrapper').html(data);
            }});
        });
    }, 0);
});

function setCookie(c_name,value,exdays)
{
    var exdate = new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value = escape(value) + ((exdays==null) ? "" : ";path=/; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}

/**
 * http://stackoverflow.com/a/10997390/11236
 */
function updateURLParameter(url, param, paramVal){
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (i=0; i<tempArray.length; i++){
            if(tempArray[i].split('=')[0] != param){
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }

    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}

function sendFile(file, editor, welEditable) {
    data = new FormData();
    data.append("image", file);
    $('body').css({'cursor' : 'wait'});
    $.ajax({
        url: $('meta[name="application-name"]').data('baseurl') + 'oc-panel/cmsimages/create',
        datatype: "json",
        type: "POST",
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function(response) {
            response = jQuery.parseJSON(response);
            if (response.link) {
                if ($("textarea[name=description], textarea[name='formorm[description]']").data('editor')=='html') {
                    $("#formorm_description, textarea[name=description], textarea[name=email_purchase_notes], .cf_textarea_fields").summernote('insertImage', response.link);
                }
                else if ($( "#crud-post" ).length || $( "#crud-category" ).length || $( "#crud-location" ).length) {
                    $("#formorm_description").summernote('insertImage', response.link);
                }
            }
            else {
                alert(response.msg);
            }
            $('body').css({'cursor' : 'default'});
        },
        error: function(response) {
            $('body').css({'cursor' : 'default'});
        },
    });
}


//Loads the correct sidebar on window load,
//collapses the sidebar on window resize.
// Sets the min-height of #page-wrapper to window size
$(function() {
    $(window).bind("load resize", function() {
        topOffset = 50;
        width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
        if (width < 768) {
            $('div.navbar-collapse').addClass('collapse');
            topOffset = 100; // 2-row-menu
        } else {
            $('div.navbar-collapse').removeClass('collapse');
        }

        height = ((this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height) - 1;
        height = height - topOffset;
        if (height < 1) height = 1;
        if (height > topOffset) {
            $("#page-wrapper").css("min-height", (height) + "px");
        }
    });

    var url = window.location;
    var element = $('ul.nav a').filter().addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});


// The close button on the dashboard
$('.close-panel').on('click',function() {
    $.cookie('intro_panel', '1', { expires: 7, path: '/' });
    $('#intro-panel').addClass('hidden');
});

$(function(){

    // Shortcut binding to elements with 'data-keybinding' attribute: trigger click-event when hotkey pressed
    clickable_selectors = [
        'a[data-keybinding]',
        'input[data-keybinding][type="submit"]',
        'input[data-keybinding][type="button"]',
        'button[data-keybinding][type="button"]'
    ]

    $(clickable_selectors).each(function(i, selector){
        $(selector).each(function(i, el){
            Mousetrap.bind($(el).data('keybinding'), function(e){
                el.click();
            });
        })
    })

    // Shortcut binding to elements with 'data-keybinding' attribute: trigger focus on these elements
    focusable_selectors = [
        'input[data-keybinding][type="text"]',
        'textarea[data-keybinding]',
    ]
    $(focusable_selectors).each(function(i, selector){
        $(selector).each(function(i, el){
            Mousetrap.bind($(el).data('keybinding'), function(e){
                el.focus();
            });
        })
    })
})

function initSelect2() {
    //select2 enable/disable
    $('select:not(".disable-select2")').select2({
        "language": "es"
    });

    //select2 responsive width
    $(window).on('resize', function() {
        $('select:not(".disable-select2")').each(function(){
            var width = $(this).parent().width();
            $(this).siblings('.select2-container').css({'width':width});
        });
    }).trigger('resize');

    //select2 on bootstrap tabs
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (event) {
        $('select:not(".disable-select2")').each(function(){
            var width = $(this).parent().width();
            $(this).siblings('.select2-container').css({'width':width});
        });
    });

    //select2 on bootstrap modals
    $('.modal').on('shown.bs.modal', function (event) {
        $('select:not(".disable-select2")').each(function(){
            var width = $(this).parent().width();
            $(this).siblings('.select2-container').css({'width':width});
        });
    });
}
