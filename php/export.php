<?php
header("Cache-Control: public");
header("Pragma: public");
header("Content-type:application/vnd.ms-excel");
header("Content-Disposition:attachment;filename=log.csv");
header('Content-Type:APPLICATION/OCTET-STREAM');
ob_start();
$header_str = "distance (mm),wind1 (m/s),pm1 (μg/m³),temperature1 (℃),humidity1 (%),wind2 (m/s),pm2 (μg/m³),temperature2 (℃),humidity2 (%),time\n";
$file_str="";
$mysqli= new mysqli('localhost','root','Daohaolaji@','mqtt');
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$mysqli->query("set names utf8mb4 ;");
$from=$_GET['from'];
$to=$_GET['to'];
$mode=$_GET['mode'];
$recipient=$_GET['recipient'];
if ($mode == 'all') {
    $sql="select * from status";
}elseif ($mode == 'clean') {
    $sql="select * from status where time < '$cleanDate'";
}else{
    $sql="select * from status where time between '$from' and '$to'";
}
$result=$mysqli->query($sql);
if($result){
    if ($result->num_rows == 0) {
        echo "No data in this period...";
        exit();
    }
    while ($row = mysqli_fetch_assoc($result)){
        $file_str.= $row['distance (mm)'].','.$row['wind1 (m/s)'].','.$row['pm1 (μg/m³)'].','.$row['temperature1 (℃)'].','.$row['humidity1 (%)'];
        $file_str.= ','.$row['wind2 (m/s)'].','.$row['pm2 (μg/m³)'].','.$row['temperature2 (℃)'].','.$row['humidity2 (%)'].','.$row['time']."\n";
    }
}else{
    echo "nonono!!!";
}
$file_str= iconv("utf-8",'gbk',$file_str);
ob_end_clean();
echo $header_str;
echo $file_str;
file_put_contents('/var/www/html/mqtt/log/log.csv',$header_str.$file_str);
if (isset($recipient)) {
    $attachment = '/var/www/html/mqtt/log/log.csv';
    if ($mode == 'all') {
        $content = "All data has been exported as CSV file attached below.";
    }else {
        $content = "Data from ".$from." to ".$to." has been exported as CSV file attached below.";
    }
    require_once('PHPMailer/mail.php');
}
if ($mode == 'clean') {
    $attachment = '/var/www/html/mqtt/log/log.csv';
}
?>
