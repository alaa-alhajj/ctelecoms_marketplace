
function pie_chart(chart, data, name, title) {
    var chartDiv = document.createElement('div');
    $('#' + chart).append(chartDiv);

    $(chartDiv).highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: title
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
                name: name,
                colorByPoint: true,
                data: data
            }]
    });
}

function pie_chart_3d(chart, data, name, title) {
    var chartDiv = document.createElement('div');
    $('#' + chart).append(chartDiv);

    $(chartDiv).highcharts({
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: title
        },
        subtitle: {
            text: name
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        series: [{
                name: 'Delivered amount',
                data: data
            }]
    });
}



function line_chart(chart, data, name, title, plus) {
    var dataNew = [];
    var chartDiv = document.createElement('div');
    plus2 = plus.replace(/'/g, "");
    plus2 = plus2.replace(/"/g, '');
    $(data).each(function() {
        $v = $(this);
        $v = $v[0];
        console.log($v);
        $v1 = $v['data'];
        dataNew.push($v1[0]);

        // alert($v1);

    });
    console.log(dataNew);

    //console.log (data[]);
    $('#' + chart).append(chartDiv);

    $(chartDiv).highcharts({
        title: {
            text: title,
            x: -20 //center
        },
        xAxis: {
            categories: plus2.split(",")

        },
        yAxis: {
            title: {
                text: title
            },
            plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{name: title,
                data: dataNew}]

    });

}


function multi_line_chart(chart, data, name, title, plus) {
  
  
           
    var dataNew = [];
    var chartDiv = document.createElement('div');
    plus2 = plus.replace(/'/g, "");
    plus2 = plus2.replace(/"/g, '');
    $(data).each(function() {
        $v = $(this);
        $v = $v[0];
        console.log($v);
        $v1 = $v['data'];
        dataNew.push($v1[0]);

        // alert($v1);

    });
    console.log(dataNew);

    //console.log (data[]);
    $('#' + chart).append(chartDiv);

    $(chartDiv).highcharts({
        title: {
            text: title,
            x: -20 //center
        },
        xAxis: {
            categories: plus2.split(",")

        },
        yAxis: {
            title: {
                text: title
            },
            plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
     series: [{name: title,
                data: dataNew}]

    });

}



function bar_chart(chart, data, name, title, plus) {
  
  
           
    var dataNew = [];
    var chartDiv = document.createElement('div');
    plus2 = plus.replace(/'/g, "");
    plus2 = plus2.replace(/"/g, '');
    $(data).each(function() {
        $v = $(this);
        $v = $v[0];
        console.log($v);
        $v1 = $v['data'];
        dataNew.push($v1[0]);

        // alert($v1);

    });
    console.log(dataNew);

    //console.log (data[]);
    
    $('#' + chart).append(chartDiv);

    $(chartDiv).highcharts({
          chart: {
            type: 'column'
        },
        title: {
            text: title,
            x: -20 //center
        },
        xAxis: {
            categories: plus2.split(",")

        },
        yAxis: {
            title: {
                text: title
            },
           
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        }, plotOptions: {
            column: {
                pointPadding: 0.2,
                borderWidth: 0
            }
        },
     series: [{name: title,
                data: dataNew}]

    });

}
