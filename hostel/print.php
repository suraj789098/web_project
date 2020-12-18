<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
}
require_once "config.php";

$rty = $cd = $rent = $mess = $oth = $tot = "----------";
$sql = $link->prepare("SELECT * FROM room,student_room,room_type WHERE room.room_ssn=student_room.room_ssn and room.type=room_type.type and usn = ?");
$sql->bind_param('s',$_SESSION["usn"]);
$sql->execute();
$row = $sql->get_result()->fetch_assoc();
if($row)
{
    $rty = $row["type"];
    $cd = $row["cd"];
    $rent = $row["rent"];
    $mess = $row["mess_advance"];
    $oth = $row["others"];
    $tot = $row["total"];
}

$sql1 = $link->prepare("SELECT * FROM payment where usn = ?");
$sql1->bind_param('s',$_SESSION["usn"]);
$sql1->execute();
$row1 = $sql1->get_result()->fetch_assoc();
if($row1)
{
    $inv = $row1["invoice"];
    $time = $row1["timestamp"];
}

$sql->close();
$sql1->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hostel Management - NMAMIT</title>
    <link rel="icon" href="Images/icon.png" type="image/png">
    <link rel="stylesheet" href="prints.css">
</head>
    
<body style="background-color:bisque">
<div style="width: 800px; margin: 0 auto;">
    <textarea id="header"> Fee Invoice</textarea>
    
    <div>
		
            <textarea id="address">N.M.A.M.Institute of Technology, Nitte,Karkala,Udupi -574110
Phone: 8258281263 </textarea>

            <div id="logo">
              <img src="images/nitte-eng.png" alt="nitteclg" style="width:500px" />
            </div>
		
    </div>
    
    <div style="clear:both"></div>

            <textarea id="name"><?php 
                echo "Name: {$_SESSION['name']}\nUSN: ".strtoupper("{$_SESSION['usn']}");?></textarea>

            <table id="meta">
                <tr>
                    <td class="meta-head">Invoice #</td>
                    <td><textarea><?php echo "$inv";?></textarea></td>
                </tr>
                <tr>

                    <td class="meta-head">Date</td>
                    <td><textarea><?php echo "$time";?></textarea></td>
                </tr>

            </table>
    <br><br>
    <div style="margin-top:100px;">
    
    <table id="items">
		
		  <tr>
		      <th>Room Type</th>
		      <th>Caution Deposit</th>
		      <th>Rent</th>
		      <th>Mess Advance</th>
              <th>Others</th>
		  </tr>
		  
		  <tr class="item-row">
		      <td><textarea><?php echo "$rty";?></textarea></td>
		      <td><textarea><?php echo "$cd";?></textarea></td>
		      <td><textarea><?php echo "$rent";?></textarea></td>
		      <td><textarea><?php echo "$mess";?></textarea></td>
		      <td><?php echo "$oth";?></td>
		  </tr> 
		  <tr>

		      <td> </td>
              <td></td>
              <td></td>
		      <td style="font-size:20px"><b>Total Paid</b></td>
		      <td><div id="total"><?php echo "Rs.$tot";?></div></td>
		  </tr>

		
		</table>

    </div>
    
    <button id="printPageButton" class="button1" onclick="window.print()">Print</button>
</div>
</body>
</html>