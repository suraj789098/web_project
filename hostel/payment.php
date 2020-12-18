<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
}
require_once "config.php";

$rty = trim($_POST["roomtype"]);
$rs="";

$sql = $link->prepare("SELECT * FROM room where type= ? and occupied='n'");
$sql->bind_param('s',$rty);
$sql->execute();
$row = $sql->get_result()->fetch_assoc();
if($row)
{
    $rs = $row["room_ssn"];
    $sql1 = $link->prepare("INSERT into student_room values(?,?)");
    $sql1->bind_param('si',$_SESSION["usn"],$rs);
    $sql1->execute();
    
    $sql2 = $link->prepare("UPDATE room set occupied='y' where room_ssn= ?");
    $sql2->bind_param('i',$rs);
    $sql2->execute();
    
    $sql3 = $link->prepare("INSERT into payment (usn) values(?)");
    $sql3->bind_param('s',$_SESSION["usn"]);
    $sql3->execute();
    
    $sql1->close();
    $sql2->close();
    $sql3->close();
    
}
else
{
    echo "<h1><b>ERROR!!!</b></h1>";
}

$sql->close;

header("location: main.php");
?>