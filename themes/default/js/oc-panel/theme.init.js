window.summernoteSettings = function () {
    return {
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
    }
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
                $("#formorm_description, textarea[name=description], textarea[name=email_purchase_notes], .cf_textarea_fields").summernote('insertImage', response.link);
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
