<?php
// $sid = $_POST['sid'];
// session_id($sid);
session_start();
if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
}
$logOut = $_POST['logOut'];
if (isset($_POST['logOut']) && $_POST['logOut']=='') {
    session_destroy();
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="js/jquery.min.js"></script>
    <script src="js/highcharts.js"></script>
    <script src="js/darkThemeHighCharts.js"></script>
    <script src="js/exporting.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-datetimepicker.min.js" charset="UTF-8"></script>
    <script type="text/javascript" src="js/locales/bootstrap-datetimepicker.fr.js" charset="UTF-8"></script>
    <link href="css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
    <script type="text/javascript" src="js/dashboard.js"></script>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="css/iconfont.css">
    <script type="text/javascript" src="js/control.js"></script>
    <title>Dashboard</title>
</head>
<body>
    <div id="topper">
        <nav class="navbar navbar-default" id="navi">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Monitor</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active"><a href="#">Dashboard</a></li>
                    <li><a id="controlbtn">Control Panel</a></li>
                    <li><a id="aboutbtn" href="about.html">About</a></li>
                    <li><a style='cursor:pointer' onclick='document.getElementById("logout").submit()'>Log Out</a></li>
                    <form id="logout" method="POST" style="display:none;">
                        <input type="text" style="display:none" name="logOut">
                    </form>
                </ul>
            </div><!--/.nav-collapse -->
        </nav>
        <!-- <div class="dashboard"> -->
        <div class="upper">
            <div id="about" style="text-align: left;">
                <h1> <span>STM32</span><span> Environmental Wind Tunnel Monitoring System</span></h1>
                <div style="overflow:hidden;">
                    <h3 id="time">Connecting...</h3>
                    <div id="indicator">
                        <div style="display:none;" id="connected">
                            <div class="status-green"></div>
                            <div class="status-green-glow"></div>
                            <div style="margin-left:25px;">CONNECTED</div>
                        </div>
                        <div id="disconnected">
                            <div class="status-red"></div>
                            <div class="status-red-glow"></div>
                            <div style="margin-left:25px;">DISCONNECTED</div>
                        </div>
                    </div>
                </div>
                <!-- <div class="container"> -->
                <div class="row">
                    <div class="col-xs-12 col-sm-2 col-md-2 col-sm-offset-1 col-md-offset-1" style="float:right; padding:0; padding-top:10px; padding-left:10px; padding-right:10px;">
                        <input type="button" class="btn btn-default col-xs-12" id="csvbtn" value="Export CSV" style="background-color:transparent; color:white;" >
                        <!-- <input type="button" class="btn btn-default col-xs-6" id="quickmail" value="(Constructing...)" style="background-color:transparent; color:white;" disabled> -->
                    </div>
                    <div class="input-group col-xs-12 col-sm-6 col-md-5" style="padding-top:10px; padding-left:10px; padding-right:10px;">
                        <input type="text" class="form-control" id="chartInterval" required placeholder="Interval Time (Seconds)" style="background-color:transparent; color:white;">
                        <span class="input-group-btn">
                            <input type="button" class="btn btn-default" id="setIntervalbtn" value="Set Interval" onclick="examineInterval()" style="background-color:transparent; color:white;">
                            <input type='button' class='btn btn-default' id="freezebtn" value='Freeze' onclick='monitor(1)' style="background-color:transparent; color:white; border-bottom-right-radius:5px;border-top-right-radius:5px;"/>
                            <input type='button' class='btn btn-default' id="resumebtn" value='Resume' onclick='monitor(0)' style="display:none; background-color:transparent; color:white;"/>
                        </span>
                    </div>

                </div>
                <!-- </div> -->
            </div>
            <!-- Control Panel -->
            <div id="control-panel" style="display:none;">
                <!-- <div class="row"> -->
                <ul class='btncontainer col-xs-12 col-sm-6 col-md-6'>
                    <li>
                        <div class='control-btn' id="ascend" onclick="ascend()">
                            <i class="iconfont icon-up-arrow"></i>
                        </div>
                    </li>
                    <li>
                        <div class='control-btn' id="descend" onclick="descend()">
                            <i class="iconfont icon-down-arrow"></i>
                        </div>
                    </li>
                    <li>
                        <div class='control-btn large'>Timer</div>
                    </li>
                </ul>
                <h3 class="col-xs-12 col-sm-6 col-md-6" id="commandSet"></h3>
                <!-- </div> -->
            </div>
            <div id="sensorValues">
                <div class="sensor-values" id="distancebtn">
                    <div class="distance" id="distance">0<span>mm</span></div>
                    <label>Distance <img src="pic/distance.png" height="20px"/></label>
                </div>
                <div class="sensor-values" id="windbtn">
                    <div class="wind" id="wind1">0<span>m/s</span></div>
                    <div class="wind" id="wind2">0<span>m/s</span></div>
                    <label>Wind <img src="pic/wind.png" height="25px"/></label>
                </div>
                <div class="sensor-values" id="pm25btn">
                    <div class="pm25" id="pm1">0<span>&#181g/m<sup>3</span></div>
                        <div class="pm25" id="pm2">0<span>&#181g/m<sup>3</span></div>
                            <label>PM2.5 <img src="pic/pm25.png" height="20px"/></label>
                        </div>
                        <div class="sensor-values" id="temperaturebtn">
                            <div class="temperature" id="temperature1">0<span>°C</span></div>
                            <div class="temperature" id="temperature2">0<span>°C</span></div>
                            <label>Temperature <img src="pic/temperature.png" height="25px"/></label>
                        </div>
                        <div class="sensor-values" id="humiditybtn">
                            <div class="humidity" id="humidity1">0<span>%</span></div>
                            <div class="humidity" id="humidity2">0<span>%</span></div>
                            <label>Humidity <img src="pic/humidity.png" height="30px"/></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lower">
                <div id="sensorData" class="contents"></div>
                <div id="windData" class="contents folded"></div>
                <div id="pm25Data" class="contents folded"></div>
                <div id="temperatureData" class="contents folded"></div>
                <div id="humidityData" class="contents folded"></div>
            </div>

            <!-- sample modal content -->
            <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="color: grey;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h2 class="modal-title" id="myModalLabel">Timer Configuration</h2>
                        </div>
                        <div class="modal-body">
                            <div class="container" style="width: 100%;">
                                <!-- <h4>Administrator Account</h4> -->
                                <table id="timertable">
                                    <thead>
                                        <tr>
                                            <th style="text-align:center;"><h4>Hovering Height</h4></th>
                                            <!-- <th></th> -->
                                            <th style="text-align:center;"><h4>Delay Time</h4></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type='text' name='position' placeholder='mm' class="form-control"/></td>
                                            <!-- <td>&#12288</td> -->
                                            <td><input type='text' name='delay' placeholder='s' class="form-control"/></td>
                                            <td><button type="button" class="btn btn-danger deleteTable" name="delbtn">Delete</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="addTable">Add</button>
                            <button type="button" class="btn btn-success" id="submitTable">Execute</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->

            <div id="exportModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="color: grey;">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h2 class="modal-title" id="exportModalLabel">Export CSV File</h2>
                        </div>
                        <div class="modal-body">
                            <div class="container" style="width: 100%;">
                                <h4>Enter the time interval of data:</h4>
                                <!-- <input type='datetime' id="fromTime" name='position' placeholder='Y-m-d h:i:s' class="form-control" required/> -->
                                <!-- <input type='datetime' id="toTime" name='delay' placeholder='Y-m-d h:i:s' class="form-control" required/> -->
                                <!-- datetimepicker -->
                                <div class="form-group">
                                    <!-- <label for="dtp_input1" class="col-md- control-label">DateTime Picking</label> -->
                                    <div class="input-group date col-xs-12" data-date-format="yyyy-mm-dd HH:ii:ss">
                                        <input class="form-control form_datetime" id="fromTime" size="16" type="text" placeholder="Start Time" value="">
                                        <span class="input-group-addon" id="clearFrom"><span class="glyphicon glyphicon-remove"></span></span>
                                        <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span> -->
                                    </div>
                                    To
                                    <!-- <input type="hidden" id="dtp_input1" value="" /><br/> -->
                                    <div class="input-group date col-xs-12" data-date-format="yyyy-mm-dd HH:ii:ss">
                                        <input class="form-control form_datetime" id="toTime" size="16" type="text" placeholder="End Time" value="">
                                        <span class="input-group-addon" id="clearTo"><span class="glyphicon glyphicon-remove"></span></span>
                                        <!-- <span class="input-group-addon"><span class="glyphicon glyphicon-th"></span></span> -->
                                    </div>
                                    <!-- <input type="hidden" id="dtp_input1" value="" /><br/> -->
                                    <input type="checkbox" id="exportall" value="Export All"><label>Export All</label>
                                </div>

                                <h5>Optionally enter your email to recieve the file:</h5>
                                <input type="email" id="recipient" name="mailAddress" class="form-control" placeholder="Email Address">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" id="downloadbtn">Download File</button>
                            <button type="button" class="btn btn-success" id="mailbtn">Send As Email</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->


            <script type="text/javascript">
            function parseTime(str) {
                try {
                    var unix = new Date(Date.parse(str.replace(/-/g, "/"))).getTime();
                    return unix;
                } catch (e) {
                    return -1;
                }
                // 2017-08-01 17:04:00
                // var r = /^20(1[7-9]|[2-9][0-9])-[\d][\d]-[\d][\d]\s([0-1][0-9]|2[0-4]):[0-5][0-9]:[0-5][0-9]$/;
                // var flag=r.test(str);
                // return flag;
            }
            $('#downloadbtn').click(
                function() {
                    if ($('#exportall').is(':checked')){
                        var url = 'php/export.php?mode=all'
                    }else {
                        var fromtime = parseTime($('#fromTime').val())
                        var totime = parseTime($('#toTime').val())
                        if (fromtime > 0 && totime > 0 && totime > fromtime ) {
                            var url = 'php/export.php?from='+$('#fromTime').val()+'&to='+$('#toTime').val()
                        }else {
                            alert('Oops! Invalid Time!')
                        }
                    }
                    if (url!=undefined) {
                        window.location.href = url
                        $('#exportModal').modal('hide')
                    }
                }
            )
            $('#mailbtn').click(
                function() {
                    if ($('#recipient').val()!=''){
                        if ($('#exportall').is(':checked')){
                            var mailurl = 'php/export.php?mode=all&recipient='+$('#recipient').val()
                        }else {
                            var fromtime = parseTime($('#fromTime').val())
                            var totime = parseTime($('#toTime').val())
                            if (fromtime > 0 && totime > 0 && totime > fromtime) {
                                var mailurl = 'php/export.php?from='+$('#fromTime').val()+'&to='+$('#toTime').val()+'&recipient='+$('#recipient').val()
                            }else {
                                alert('Oops! Invalid Time!')
                            }
                        }
                    }else {
                        alert('Specify a recipient to receive the email!')
                    }
                    if (mailurl!=undefined) {
                        $.ajax({
                            url: mailurl,
                            success: function(feedback) {
                                $('#commandSet').slideDown().html(feedback)
                            },
                            cache: false
                        });
                        $('#exportModal').modal('hide')
                    }
                }
            )
            $('#exportall').click(
                function() {
                    if ($('#exportall').is(':checked')){
                        $('#fromTime').attr('disabled',true)
                        $('#toTime').attr('disabled',true)
                    }else {
                        $('#fromTime').attr('disabled',false)
                        $('#toTime').attr('disabled',false)
                    }
                }
            )

            $('#csvbtn').click(
                function() {
                    $('#exportModal').modal('show')
                }
            )
            // $('#mailbtn').click(
            //     function() {
            //         $.ajax({
            //             url:'php/PHPMailer/mail.php',
            //             success: function(feedback) {
            //                 $('#commandSet').slideDown().html(feedback)
            //             },
            //             cache: false
            //         });
            //     }
            // )
            function isPositiveInteger(str) {
                var r = /^\+?[1-9][0-9]*$/;　　//正整数
                var flag=r.test(str);
                return flag;
            }
            $('.control-btn.large').click(function () {
                $('#myModal').modal("show")
            });
            $('.control-btn').click(function () {
                $(this).toggleClass('active');
            });

            $('#controlbtn').click(
                function() {
                    $('#control-panel').slideToggle()
                }
            )

            //datetimepicker
            // $('#fromTime').datetimepicker('setStartDate', '2017-08-01');
            $('.form_datetime').datetimepicker({
                //language:  'fr',
                weekStart: 1,
                todayBtn:  1,
                autoclose: 1,
                todayHighlight: 1,
                startView: 2,
                forceParse: 0,
                showMeridian: 1
            });
            $('#clearFrom').click(
                function() {
                    $('#fromTime').val('')
                }
            )
            $('#clearTo').click(
                function() {
                    $('#toTime').val('')
                }
            )
            </script>
        </body>
        </html>
