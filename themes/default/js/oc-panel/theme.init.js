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
        imageAttributes: {
            icon: '<i class="note-icon-pencil"/>',
            figureClass: 'figureClass',
            figcaptionClass: 'captionClass',
            captionText: 'Image Attributes.',
            manageAspectRatio: false // true = Lock the Image Width/Height, Default to true
        },
        popover: {
            image: [
                ['custom', ['imageAttributes']],
                ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                ['float', ['floatLeft', 'floatRight', 'floatNone']],
                ['remove', ['removeMedia']]
            ],
        },
        callbacks: {
            onInit: function() {
                $(".note-placeholder").text($(this).attr('placeholder'));
                $(".note-modal[aria-label='Change Image Attributes'] .form-group").addClass('note-form-group');
                $(".note-modal[aria-label='Change Image Attributes'] .note-image-title-btn").addClass('note-btn-primary');
                $(".note-modal[aria-label='Change Image Attributes'] .form-group").filter(':eq(2), :eq(3), :eq(4), :eq(5)').remove();
            },
            onPaste: function (e) {
                var text = (e.originalEvent || e).clipboardData.getData('text/plain');
                e.preventDefault();
                    document.execCommand('insertText', false, text);
            },
            onImageUpload: function(files, editor, welEditable) {
                sendFile(files[0], editor, welEditable);
            },
            onBlurCodeview: function () {
                let codeviewHtml = $(this).summernote('code');
                $(this).val(codeviewHtml);
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
