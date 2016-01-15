function init_panel()
{
    if ($("textarea[name=description]").data('editor')=='html')
    {
        $("#formorm_description, textarea[name=description], textarea[name=email_purchase_notes], .cf_textarea_fields").summernote({
            height: "450",
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
            onPaste: function (e) {
                var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                e.preventDefault();
                document.execCommand('insertText', false, text);
            },
            onImageUpload: function(files, editor, welEditable) {
                sendFile(files[0], editor, welEditable);
            }
        });
    }
	else if ($( "#crud-post" ).length || $( "#crud-category" ).length || $( "#crud-location" ).length) {
		$("#formorm_description").summernote({
            height: "350",
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
            onPaste: function (e) {
                var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                e.preventDefault();
                document.execCommand('insertText', false, text);
            },
            onImageUpload: function(files, editor, welEditable) {
                sendFile(files[0], editor, welEditable);
            }
        });
	}
    else
    {   
        $('#formorm_description, textarea[name=description]:not(.disable-bbcode), textarea[name=email_purchase_notes], .cf_textarea_fields').sceditorBBCodePlugin({
            toolbar: "bold,italic,underline,strike|left,center,right,justify|" +
            "bulletlist,orderedlist|link,unlink,image,youtube|source",
            resizeEnabled: "true",
            emoticonsEnabled: false,
            style: $('meta[name="application-name"]').data('baseurl') + "themes/default/css/jquery.sceditor.default.min.css",
            enablePasteFiltering: "true"});
    }
	// paste plain text in sceditor
	$(".sceditor-container iframe").contents().find("body").bind('paste', function(e) {
		e.preventDefault();
		var text = (e.originalEvent || e).clipboardData.getData('text/plain');
		$(".sceditor-container iframe")[0].contentWindow.document.execCommand('insertText', false, text);
	});	
    
    // $('#formorm_description, textarea[name=description]').sceditorBBCodePlugin({
    //     toolbar: "bold,italic,underline,strike|left,center,right,justify|" +
    //     "bulletlist,orderedlist|link,unlink,image,youtube|source",
    //     resizeEnabled: "true"});
    
    $('.tips').popover();

    $("select").chosen({
        no_results_text: getChosenLocalization("no_results_text"),
        placeholder_text_multiple: getChosenLocalization("placeholder_text_multiple"),
        placeholder_text_single: getChosenLocalization("placeholder_text_single"),
        width: "100%"
        });
    $('select').each(function(){
        if($(this).hasClass('disable-chosen')){
            $(this).chosen('destroy');      
        } 
    });
    
    $('.radio > input:checked').parentsUntil('div .accordion').addClass('in');
    
    //custom fields select. To determain if some fields are shown or not
    $('select#cf_type_fileds').change(function(){ // on change add hidden   
        if($(this).val() == 'select' || $(this).val() == 'radio'){
            $('#cf_values_input').attr('type','text');
            $('#cf_values_input').parent().parent().css('display','block'); // parent of a parent. display whole block
        }
        else{
            $('#cf_values_input').attr('type','hidden');
            $('#cf_values_input').parent().parent().css('display','none'); // parent of a parent. dont show whole block
        }    
    }).change();
    
    // custom field edit, show/hide values field
    $('#cf_values_input').parent().parent().css('display','none');
    if( $('#cf_type_field_input').attr('value') == 'select' 
        || $('#cf_type_field_input').attr('value') == 'radio') 
            $('#cf_values_input').parent().parent().css('display','block'); 

    
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
	
	// Load google api
	$.getScript(("https:" == document.location.protocol ? "https:" : "http:") + "//www.google.com/jsapi");
	
	// Call open_eshop.init function only if exist
	if (typeof open_eshop !== 'undefined' && $.isFunction(open_eshop.init)) {open_eshop.init(open_eshop);}
	
	// Modal confirmation
	$('[data-toggle="confirmation"]').click(function(event) {
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
                if ($("textarea[name=description]").data('editor')=='html') {
                    $("#formorm_description, textarea[name=description], textarea[name=email_purchase_notes], .cf_textarea_fields").summernote('editor.insertImage', response.link);
                }
                else if ($( "#crud-post" ).length || $( "#crud-category" ).length || $( "#crud-location" ).length) {
                    $("#formorm_description").summernote('editor.insertImage', response.link);
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

// Initiate the mainmenu
$(function() {
    $('#side-menu').metisMenu();
});

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
    var element = $('ul.nav a').filter(function() {
        return this.href == url || url.href.indexOf(this.href) == 0;
    }).addClass('active').parent().parent().addClass('in').parent();
    if (element.is('li')) {
        element.addClass('active');
    }
});


// Collapse the sidebar menu
$( "#collapse-menu" ).click(function() {
  $( "body" ).toggleClass( "folded" );
});

// The close button on the dashboard
$('.close-panel').on('click',function(){
  $('#intro-panel').addClass('hidden');
});


// Initiate fastclick
$(function() {
    FastClick.attach(document.body);
});
