<?php
require("phpMQTT.php");
echo "<h1 align='center'>Console</h1>";
$mqtt = new phpMQTT("kyrie.top", 1883, "C_15000908");
if(!$mqtt->connect()){
    exit(1);
}
$topics['test1'] = array("qos"=>0, "function"=>"procmsg");
$mqtt->subscribe($topics,0);
echo str_repeat(" ",1024);
while ($mqtt->proc()) {
    ob_flush();
    flush();
    echo '<script>window.scrollTo(0,document.body.scrollHeight);</script>';
    usleep(50000);
}
$msqtt->close();
function procmsg($topic,$msg){
        date_default_timezone_set(PRC);
        echo substr(date("r"),0,25)."\tTopic: {$topic}\t  Content: $msg\n";
        echo "<br />";
        if (substr($msg,0,4)=="AA00") {
            include("db_config.php");
            $conn = new mysqli($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
            $conn->query("set names 'utf8mb4'");
            $time=date("Y-m-d H:i:s");
            $pattern="/^AA00w=([\d|\.]{0,4}[\d]{0,4})\/([\d|\.]{0,4}[\d]{0,4})&p=([\d|\.]{0,4}[\d]{0,4})\/([\d|\.]{0,4}[\d]{0,4})&d=([\d]{1,4})&t=([\d|\.]{0,4}[\d]{0,5})\/([\d|\.]{0,4}[\d]{0,5})&h=([\d|\.]{0,4}[\d]{0,4})\/([\d|\.]{0,4}[\d]{0,4})/";
            preg_match_all($pattern,$msg,$matches);
            $distance=$matches[5][0];
            $wind1=$matches[1][0];
            $pm1=$matches[3][0];
            $temperature1=$matches[6][0];
            $humidity1=$matches[8][0];
            $wind2=$matches[2][0];
            $pm2=$matches[4][0];
            $temperature2=$matches[7][0];
            $humidity2=$matches[9][0];
            // print_r($matches);
            if (!($distance==""||$wind1==""||$pm1==""||$temperature1==""||$humidity1==""||$wind2==""||$pm2==""||$temperature2==""||$humidity2=="")) {
                $distance = (int)$distance;
                $wind1 = (float)$wind1;
                $pm1 = (float)$pm1*10;
                $temperature1 = (float)$temperature1;
                $humidity1 = (float)$humidity1;
                $wind2 = (float)$wind2;
                $pm2 = (float)$pm2*10;
                $temperature2 = (float)$temperature2;
                $humidity2 = (float)$humidity2;
                $sql="INSERT INTO status values(UUID(),$distance,$wind1,$pm1,$temperature1,$humidity1,$wind2,$pm2,$temperature2,$humidity2,'$time');";
                $result=$conn->query($sql);
            }else{
		        echo "data error";
	        }
            $conn->close();
        }
}
?>
