<?php
$conn = new mysqli('localhost','root','Daohaolaji@','mqtt');
echo str_repeat(" ",1024);

// $n = 0;
while (true) {
    // $mqtt->connect();
    ob_flush();
    flush();
    // $n = $n + 1;
    $wind1 = 5.2 + floating();
    $wind2 = 5.2 + floating();
    $pm1 = 52 + floating();
    $pm2 = 47 + floating();
    $distance = 120 + floating();
    $temp1 = 26.7 + floating();
    $temp2 = 26.6 + floating();
    $hum1 = 84.2 + floating();
    $hum2 = 81.4 + floating();

    $time=date("Y-m-d H:i:s");
    $sql = "INSERT INTO status values(UUID(),$distance,$wind1,$pm1,$temp1,$hum1,$wind2,$pm2,$temp2,$hum2,'$time');";
    $conn->query($sql);
    // $mqtt->close();
    echo $sql.'<br />';
    sleep(1);
}

$conn->close();

function floating(){
    // if (mt_rand(0,100) > 98){
    //     $error = mt_rand(5,10);
    // }elseif (mt_rand(0,100)<2) {
    //     $error = mt_rand(10,20);
    // }else {
        $error = 1;
    // }
    return mt_rand(-10,10)/10 * $error;
}
?>
