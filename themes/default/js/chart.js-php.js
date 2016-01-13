// Set some global variables
Chart.defaults.global.scaleLabel = "   <%=value%>";

// Will be filled with canvas
var ChartJSPHP = new Array();

// You must call this function after document.ready
function loadChartJsPhp() {
    // Getting all chart.js canvas
    var elements = document.querySelectorAll("[data-chartjs]");

    console.log(elements);

    // Looping every canvas
    for (var i in elements)
    {
        // Escaping length and item in the loop
        if (i === 'length' || i === 'item') {
            continue;
        }
        var canvas = elements[i];
        var id = canvas.id;

        // Getting ctx from canvas
        if (typeof canvas.getContext == 'function')
        {
            var ctx = canvas.getContext('2d');
            // Getting values in data attributes
            var htmldata = canvas.dataset;
            var data = JSON.parse(htmldata.data);
            var type = htmldata.chartjs;
            var options = JSON.parse(htmldata.options);

            // Creating chart and saving for later use
            ChartJSPHP[id] = new Chart(ctx)[type](data, options);
        }
    }
};

(function() {
    loadChartJsPhp();
})();

//  Destroying and creating each chart when  show/hide bootstrp tab
$('a[data-toggle=tab').on('shown.bs.tab', function (e) {

    for (var i in ChartJSPHP) {
        if (typeof ChartJSPHP[i].destroy == 'function')
            ChartJSPHP[i].destroy();
    }
    
    loadChartJsPhp();
});
