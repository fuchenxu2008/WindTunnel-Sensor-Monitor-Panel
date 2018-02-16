<?php
    $recipient = '553597230@qq.com';
    $mysqli= new mysqli('localhost','root','Daohaolaji@','mqtt');
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    $mysqli->query("set names utf8mb4 ;");
    $newsql = "SELECT time from status ORDER BY time DESC LIMIT 0,1";
    $newresult = $mysqli->query($newsql);
    if ($newresult) {
        $newrow = mysqli_fetch_assoc($newresult);
        $latest = $newrow['time'];
    }
    $cleanDate = getCleanDate($latest,30);
    $nextDelDay = getNextDelDay($cleanDate);
    $sql = "SELECT * FROM status WHERE time < '$cleanDate'";
    $result = $mysqli->query($sql);
    if ($result) {
        $num = $result->num_rows;
        if ($num > 0) {
            $mode = 'clean';
            require_once('export.php');
            $delsql = "DELETE FROM status WHERE time < '$cleanDate'";
            $delresult = $mysqli->query($delsql);
            if ($delresult) {
                $content = "Data of yesterday has been cleaned.";
                echo $content;
                require_once('PHPMailer/mail.php');
            }
        }else {
            $nextsql = "SELECT * FROM status WHERE time < '$nextDelDay'";
            $nextresult = $mysqli->query($nextsql);
            if ($nextresult) {
                $num2 = $nextresult->num_rows;
                if ($num2 > 0) {
                    $content = "Be aware: You have data to be cleaned tomorrow.";
                    echo $content;
                    require_once('PHPMailer/mail.php');
                }
            }
        }
    }
    $mysqli->close();
    function getCleanDate($latestTime,$preservedDays){
        $stamp = strtotime($latestTime) - 60*60*24*$preservedDays;
        $obsoleteDate = date("Y-m-d",$stamp);
        // echo $obsoleteDate;
        return $obsoleteDate;
    }
    function getNextDelDay($delDay){
        return date('Y-m-d',strtotime('+1d',strtotime($delday)));
    }
?>
