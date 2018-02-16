<?php
include("db_config.php");
$conn=new mysqli($mysql_server_name,$mysql_username,$mysql_password,$mysql_database);
$conn->query("set names 'utf8mb4'");
$sql="SELECT * FROM status ORDER BY time DESC LIMIT 0,1";
$result=$conn->query($sql);
$row = $result->fetch_row();
$uuid=$row[0];
$distance=(int)$row[1];
$wind1=(float)$row[2];
$pm1=(float)$row[3];
$temperature1=(float)$row[4];
$humidity1=(float)$row[5];
$wind2=(float)$row[6];
$pm2=(float)$row[7];
$temperature2=(float)$row[8];
$humidity2=(float)$row[9];
$time=$row[10];
$conn->close();
header("Content-type: text/json");
$data = array('uuid'=>$uuid,'distance'=>$distance,'wind1'=>$wind1,'pm1'=>$pm1,'temperature1'=>$temperature1,'humidity1'=>$humidity1,'wind2'=>$wind2,'pm2'=>$pm2,'temperature2'=>$temperature2,'humidity2'=>$humidity2,'time'=>$time);

echo json_encode($data);
?>
