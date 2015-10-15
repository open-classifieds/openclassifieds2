$(function  () {
    var group = $("ol.plholder").sortable({
        group: 'plholder',
        delay: 100,
        onDrop: function (item, container, _super) {
            //first we execute the normal plugins behaviour
            _super(item, container);

            //where we drop the location
            var parent = $(item).parent();

            //values of the list
            val = $(parent).sortable().sortable('serialize').get();
            val = val[0].split(',');

            //how deep are we? we don't need it we process it in the php
            var deep = $(item).parentsUntil($("ol.plholder"),'ol')['length'];  

            //building data to send
            var data = {
                  "id_location" : $(item).data('id'),
                  "id_location_parent" : $(parent).data('id'),
                  //"order" : $.inArray($(item).attr('id'),val),
                  "deep" : deep,
                  "brothers" : val,
                };

            //saving the order
            $.ajax({
                type: "GET",
                url: $('#ajax_result').data('url'),
                beforeSend: function(text) {
                    $('#ajax_result').text('Saving').removeClass().addClass("label label-warning");
                    $("ol.plholder").sortable('disable');
                    $('ol.plholder').animate({opacity: '0.5'});
                },
                data: data,
                success: function(text) {
                    $('#ajax_result').text(text).removeClass().addClass("label label-success");
                    $("ol.plholder").sortable('enable');
                    $('ol.plholder').animate({opacity: '1'});
                }               
            });
        
             
        },
        serialize: function (parent, children, isContainer) {
             return isContainer ? children.join() : parent.attr("id");
        },

    })
})

$(function(){
    $("a.index-delete").click(function(e) {
        var href = $(this).attr('href');
        var title = $(this).attr('title');
        var text = $(this).data('text');
        var id = $(this).data('id');
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
            $("ol.plholder").sortable('disable');
            $.ajax({ url: href,
                }).done(function ( data ) {
                    $('#'+id).hide("slow");
                    window.location.href = window.location.href;
            });
        });
    }); 
});

function JSONscriptRequest(fullUrl) {
    // REST request path
    this.fullUrl = fullUrl; 
    // Keep IE from caching requests
    this.noCacheIE = '&noCacheIE=' + (new Date()).getTime();
    // Get the DOM location to put the script tag
    this.headLoc = document.getElementsByTagName("head").item(0);
    // Generate a unique script tag id
    this.scriptId = 'YJscriptId' + JSONscriptRequest.scriptCounter++;
}

// Static script ID counter
JSONscriptRequest.scriptCounter = 1;

// buildScriptTag method
//
JSONscriptRequest.prototype.buildScriptTag = function () {

    // Create the script tag
    this.scriptObj = document.createElement("script");

    // Add script object attributes
    this.scriptObj.setAttribute("type", "text/javascript");
    this.scriptObj.setAttribute("src", this.fullUrl + this.noCacheIE);
    this.scriptObj.setAttribute("id", this.scriptId);
}

// removeScriptTag method
// 
JSONscriptRequest.prototype.removeScriptTag = function () {
    // Destroy the script tag
    this.headLoc.removeChild(this.scriptObj);  
}

// addScriptTag method
//
JSONscriptRequest.prototype.addScriptTag = function () {
    // Create the script tag
    this.headLoc.appendChild(this.scriptObj);
}

var whos = 'continent';

function getPlaces(gid,src)
{	
    whos = src;
    lang = $('#auto_locations_lang').val();

    var request = "http://www.geonames.org/childrenJSON?geonameId="+gid+"&callback=listPlaces&style=long&lang="+lang;
    aObj = new JSONscriptRequest(request);
    aObj.buildScriptTag();
    aObj.addScriptTag();	
}

function listPlaces(jData)
{
    var import_items = [];
    counts = jData.geonames.length < jData.totalResultsCount ? jData.geonames.length : jData.totalResultsCount;
    who = document.getElementById(whos);
    who.options.length = 0;
    
    $('#group-'+whos).show();

    if (counts)
    {
        who.options[who.options.length] = new Option('Select','');
    }
    else
    {
        who.options[who.options.length] = new Option('No Data Available','NULL');
    }

    for(var i=0;i<counts;i++) {
        who.options[who.options.length] = new Option(jData.geonames[i].name,jData.geonames[i].geonameId);
        import_items.push({name:jData.geonames[i].name,lat:jData.geonames[i].lat,long:jData.geonames[i].lng});
    }

    $("#auto_locations_import").html($('label[for='+ whos +']').data('action'));
    $('#auto_locations').val(JSON.stringify(import_items));

    jData = null;
}

$(function  ()
{
    $('#group-country').hide();
    $('#group-region').hide();
    $('#group-province').hide();
    $('#group-city').hide();
    getPlaces(6295630,'continent');
    
    $("#auto_locations_import_reset").click(function() {
        $('#group-country').hide();
        $('#group-region').hide();
        $('#group-province').hide();
        $('#group-city').hide();
        getPlaces(6295630,'continent');
    });
});