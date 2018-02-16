var chart,windChart,pm25Chart,temperatureChart,humidityChart;
var interval = 1000
var freeze = false
var customMode = false
var preUUID=''
var connecting = ''
var DataMapping

function restartRequest() {
    clearTimeout(DataMapping)
    requestData()
}

function changeInterval(value) {
    if(!customMode && value!=1000){
        interval = value;
        $("#setIntervalbtn").css("background-color","#8DF078")
        customMode = true
    }else if (value!=interval && value!=1000) {
        interval = value
    }else if (value==interval && value==1000 ) {
        return
    }else{
        customMode = false
        $("#setIntervalbtn").css("background-color","transparent")
        $('#chartInterval').val(1);
        interval = 1000;
    }
    restartRequest()
}
function monitor(param){
    if(param == 1){
        freeze = true
        clearTimeout(DataMapping)
        $("#freezebtn").hide();
        $("#resumebtn").show();
    }else{
        freeze = false
        restartRequest()
        $("#resumebtn").hide();
        $("#freezebtn").show();
    }
}
function requestData() {
    $.ajax({
        url: 'php/realtime.php',
        success: function(point) {
            var series = chart.series[0], shift = series.data.length > 20;
            var x = new Date(Date.parse((point.time).replace(/-/g, "/"))).getTime();
            var currentTime = Date.parse(new Date());
            var timeGap = currentTime - x;
            var uuid = point.uuid
            var distance = point.distance
            var wind1 = point.wind1
            var wind2 = point.wind2
            var pm1 = point.pm1
            var pm2 = point.pm2
            var temperature1 = point.temperature1
            var temperature2 = point.temperature2
            var humidity1 = point.humidity1
            var humidity2 = point.humidity2
            console.log(preUUID)
            if (preUUID!='') {
                if (preUUID == uuid && timeGap >= 2000) {
                        console.log(currentTime+' VS '+x);
                        $('#disconnected').show()
                        $('#connected').hide()
                        $('#time').html("Connecting...")
                        $('#distance').html(0 + '<span>mm</span>')
                        $('#wind1').html(0 + '<span>m/s</span>')
                        $('#wind2').html(0 + '<span>m/s</span>')
                        $('#pm1').html(0 + '<span>&#181g/m<sup>3</span>')
                        $('#pm2').html(0 + '<span>&#181g/m<sup>3</span>')
                        $('#temperature1').html(0 + '<span>°C</span>')
                        $('#temperature2').html(0 + '<span>°C</span>')
                        $('#humidity1').html(0 + '<span>%</span>')
                        $('#humidity2').html(0 + '<span>%</span>')
                }else{
                    $('#disconnected').hide()
                    $('#connected').show()
                    chart.series[0].addPoint([x,wind1], true, shift);
                    chart.series[1].addPoint([x,wind2], true, shift);
                    chart.series[2].addPoint([x,pm1], true, shift);
                    chart.series[3].addPoint([x,pm2], true, shift);
                    chart.series[4].addPoint([x,temperature1], true, shift);
                    chart.series[5].addPoint([x,temperature2], true, shift);
                    chart.series[6].addPoint([x,humidity1], true, shift);
                    chart.series[7].addPoint([x,humidity2], true, shift);

                    windChart.series[0].addPoint([x,wind1], true, shift);
                    windChart.series[1].addPoint([x,wind2], true, shift);

                    pm25Chart.series[0].addPoint([x,pm1], true, shift);
                    pm25Chart.series[1].addPoint([x,pm2], true, shift);

                    temperatureChart.series[0].addPoint([x,temperature1], true, shift);
                    temperatureChart.series[1].addPoint([x,temperature2], true, shift);

                    humidityChart.series[0].addPoint([x,humidity1], true, shift);
                    humidityChart.series[1].addPoint([x,humidity2], true, shift);
                    $('#time').html(point.time)
                    $('#distance').html(distance + '<span>mm</span>')
                    $('#wind1').html(wind1 + '<span>m/s</span>')
                    $('#wind2').html(wind2 + '<span>m/s</span>')
                    $('#pm1').html(pm1 + '<span>&#181g/m<sup>3</span>')
                    $('#pm2').html(pm2 + '<span>&#181g/m<sup>3</span>')
                    $('#temperature1').html(temperature1 + '<span>°C</span>')
                    $('#temperature2').html(temperature2 + '<span>°C</span>')
                    $('#humidity1').html(humidity1 + '<span>%</span>')
                    $('#humidity2').html(humidity2 + '<span>%</span>')
                }
            }
            preUUID = uuid
            if (!freeze) {
                DataMapping = setTimeout(requestData, interval);
            }
        },
        cache: false
    });
}

window.onload = function(){
    chart.reflow();
}

function examineInterval(){
    var intervalSet = parseFloat($('#chartInterval').val())
    if(!isNaN(intervalSet)){
        if (intervalSet >= 1) {
            changeInterval($('#chartInterval').val()*1000)
        }else {
            alert("Interval cannot be less than 1s!")
        }
    }else {
        alert("Invalid Input!")
    }
}

$(document).ready(function() {
    // $("#sensorData").highcharts().reflow();
    Highcharts.setOptions({
        global: {useUTC: false},
        plotOptions: {series: {marker: {enabled: true}}},
        tooltip: {enabled: true}
    });
    chart = new Highcharts.Chart({
        chart: {
            renderTo: 'sensorData',
            defaultSeriesType: 'spline',
            events: {load: requestData}
        },
        title: {text: 'Sensor Data'},
        exporting: {
          //enabled:true,默认为可用，当设置为false时，图表的打印及导出功能失效
          buttons:{	//配置按钮选项
              printButton:{	//配置打印按钮
                  width:50,
                  symbolSize:20,
                  borderWidth:2,
                  borderRadius:0,
                  hoverBorderColor:'red',
                  height:30,
                  symbolX:25,
                  symbolY:15,
                  x:-200,
                  y:20
              },
              exportButton:{	//配置导出按钮
                  width:50,
                  symbolSize:20,
                  borderWidth:2,
                  borderRadius:0,
                  hoverBorderColor:'red',
                  height:30,
                  symbolX:25,
                  symbolY:15,
                  x:-150,
                  y:20
              },
          },
          filename:'52wulian.org',//导出的文件名
          type:'image/png',//导出的文件类型
          width:800	//导出的文件宽度
        },
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 100
        },
        yAxis: [{
            title: {
                text: 'WIND',
                style: {color: '#2b908f',font: '13px sans-serif'}
            },
            min: 0,
            max: 20,
            plotLines: [{value: 0,width: 1,color: '#808080'}]
        }, {
            title: {
                text: 'PM2.5',
                style: {color: '#90ee7e',font: '13px sans-serif'}
            },
            min: 0,
            max: 250,
            // opposite: true,
            plotLines: [{value: 0,width: 1,color: '#808080'}]
        }, {
            title: {
                text: 'TEMPERATURE',
                style: {color: '#f45b5b',font: '13px sans-serif'}
            },
            min: 0,
            max: 50,
            opposite: true,
            plotLines: [{value: 0,width: 1,color: '#808080'}]
        }, {
            title: {
                text: 'HUMIDITY',
                style: {color: '#F8F00E',font: '13px sans-serif'}
            },
            min: 0,
            max: 100,
            opposite: true,
            plotLines: [{value: 0,width: 1,color: '#808080'}]
        }],//tooltip down here
        series: [{
            name: 'WIND 1',
            yAxis: 0,
            color: '#00C7C8',
            data: []
        },{
            name: 'WIND 2',
            yAxis: 0,
            dashStyle: 'longdash',
            color: '#00C7C8',
            data: []
        },{
            name: 'PM2.5_1',
            yAxis: 1,
            color: '#7EF165',
            data: []
        },{
            name: 'PM2.5_2',
            yAxis: 1,
            dashStyle: 'longdash',
            color: '#7EF165',
            data: []
        },{
            name: 'TEMPERATURE 1',
            yAxis: 2,
            color: '#F14146',
            data: []
        },{
            name: 'TEMPERATURE 2',
            yAxis: 2,
            dashStyle: 'longdash',
            color: '#F14146',
            data: []
        },{
            name: 'HUMIDITY 1',
            yAxis: 3,
            color: '#F8F200',
            data: []
        },{
            name: 'HUMIDITY 2',
            yAxis: 3,
            dashStyle: 'longdash',
            color: '#F8F200',
            data: []
        }],
        credits: {
             enabled: false
        }
    });
//WIND
    windChart = new Highcharts.Chart({
        chart: {
            renderTo: 'windData',
            defaultSeriesType: 'spline',
            events: {
                // load: requestData
            }
        },
        title: {text: 'Wind Data'},
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 100
        },
        yAxis: [{
            title: {
                text: 'WIND',
                style: {color: '#2b908f',font: '13px sans-serif'}
            },
            min: 0,
            max: 20,
            plotLines: [{value: 0,width: 1,color: '#808080'}]
        }],
        //tooltip down here
        series: [{
            name: 'WIND 1',
            color: '#2b908f',
            data: []
            // plotOptions: {
            //     series: {
            //         dashStyle:'longdash'
            //     }
            // }
        },{
            name: 'WIND 2',
            dashStyle: 'longdash',
            color: '#2b908f',
            data: []
            // dashStyle: 'longdash'
        }],
        credits: {
             enabled: false
        }
    });
//pm2.5
    pm25Chart = new Highcharts.Chart({
        chart: {
            renderTo: 'pm25Data',
            defaultSeriesType: 'spline',
            events: {
                // load: requestData
            }
        },
        title: {text: 'PM2.5 Data'},
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 100
        },
        yAxis: [{
            title: {
                text: 'PM2.5',
                style: {color: '#2b908f',font: '13px sans-serif'}
            },
            min: 0,
            max: 350,
            plotLines: [{value: 0,width: 1,color: '#808080'}]
        }],
        //tooltip down here
        series: [{
            name: 'PM2.5_1',
            color: '#7EF165',
            data: []
        },{
            name: 'PM2.5_2',
            dashStyle: 'longdash',
            color: '#7EF165',
            data: []
        }],
        credits: {
             enabled: false
        }
    });
//temperature
    temperatureChart = new Highcharts.Chart({
        chart: {
            renderTo: 'temperatureData',
            defaultSeriesType: 'spline',
            events: {
                // load: requestData
            }
        },
        title: {text: 'Temperature Data'},
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 100
        },
        yAxis: [{
            title: {
                text: 'Temperature',
                style: {color: '#2b908f',font: '13px sans-serif'}
            },
            min: 0,
            max: 50,
            plotLines: [{value: 0,width: 1,color: '#808080'}]
        }],
        //tooltip down here
        series: [{
            name: 'temperature1',
            color: '#F14146',
            data: []
        },{
            name: 'temperature2',
            dashStyle: 'longdash',
            color: '#F14146',
            data: []
        }],
        credits: {
             enabled: false
        }
    });
//humidity
    humidityChart = new Highcharts.Chart({
        chart: {
            renderTo: 'humidityData',
            defaultSeriesType: 'spline',
            events: {
                // load: requestData
            }
        },
        title: {text: 'Humidity Data'},
        xAxis: {
            type: 'datetime',
            tickPixelInterval: 100
        },
        yAxis: [{
            title: {
                text: 'Humidity',
                style: {color: '#2b908f',font: '13px sans-serif'}
            },
            min: 0,
            max: 100,
            plotLines: [{value: 0,width: 1,color: '#808080'}]
        }],
        //tooltip down here
        // plotOptions: {
        //     series: {
        //         color: '#000000'
        //     }
        // },
        series: [{
            name: 'humidity1',
            color: '#F8F200',
            data: []
        },{
            name: 'humidity2',
            dashStyle: 'longdash',
            color: '#F8F200',
            data: []

        }],
        credits: {
             enabled: false
        }
    });
    $('#distancebtn').click(
        function() {
            $("#windData").addClass("folded")
            $("#pm25Data").addClass("folded")
            $("#temperatureData").addClass("folded")
            $("#humidityData").addClass("folded")
            $("#sensorData").removeClass("folded")
        }
    );
    $('#windbtn').click(
        function() {
            $("#sensorData").addClass("folded")
            $("#pm25Data").addClass("folded")
            $("#temperatureData").addClass("folded")
            $("#humidityData").addClass("folded")
            $("#windData").removeClass("folded")
        }
    );
    $('#pm25btn').click(
        function() {
            $("#sensorData").addClass("folded")
            $("#windData").addClass("folded")
            $("#temperatureData").addClass("folded")
            $("#humidityData").addClass("folded")
            $("#pm25Data").removeClass("folded")
        }
    );
    $('#temperaturebtn').click(
        function() {
            $("#sensorData").addClass("folded")
            $("#pm25Data").addClass("folded")
            $("#windData").addClass("folded")
            $("#humidityData").addClass("folded")
            $("#temperatureData").removeClass("folded")
        }
    );
    $('#humiditybtn').click(
        function() {
            $("#sensorData").addClass("folded")
            $("#pm25Data").addClass("folded")
            $("#temperatureData").addClass("folded")
            $("#windData").addClass("folded")
            $("#humidityData").removeClass("folded")
        }
    );
});
