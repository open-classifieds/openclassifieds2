$(function(){
	var divContents = $("#print").html();
    window.document.write('<html><body>');
    window.document.write(divContents);
    window.document.write('</body></html>');
    $('body').css({"margin-top": "30px"});
    $('body').wrapInner('<div class="container"></div>');
    $('head').append( $('<link rel="stylesheet" type="text/css" />').attr('href', '//cdn.jsdelivr.net/bootstrap/3.3.7/css/bootstrap.css') );
    window.print();
});
