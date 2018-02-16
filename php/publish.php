<?php
$topic=$_GET['topic'];
$message=$_GET['message'];
// require("../phpMQTT.php");
// $mqtt = new phpMQTT("kyrie.top", 1883, "C_15000908");
// $stat = $mqtt->connect();
// echo str_repeat(" ",1024);
// for($i=0;$i<10;$i++){
//     echo $i."<br>";
//     ob_flush();
//     flush();
//     sleep(1);
// }
echo $message;
if (isset($topic) && isset($message)) {
    publish_msg($topic,$message);
}

function publish_msg($Topic,$Msg){
    // echo "Published at ".date("r")."<br />";
    require("phpMQTT.php");
    // confirm();
    $mqtt = new phpMQTT("kyrie.top", 1883, "C_15000908");
    if ($mqtt->connect()) {
        $mqtt->publish($Topic,$Msg,0);
        $mqtt->close();
    }
}
// $mqtt->close();
?>
