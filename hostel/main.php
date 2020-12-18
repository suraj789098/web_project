<?php
    session_start();
    if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
        header("location: login.php");
        exit;
}
require_once "config.php";
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hostel Management - NMAMIT</title>
    <link rel="icon" href="Images/icon.png" type="image/png">
    <link rel="stylesheet" href="Style.css">
</head>

<body style="background-color:bisque">
    <!--Header-->
    <div id="hd" class="head" >
    <a href="https://nmamit.nitte.edu.in/index.php" target="_blank" title="Nitte Mahalinga Adyanthaya Memorial Institute of Technology">
    <img src="Images/nitte-eng.png" style="margin-left: 20px" width="32%" height="auto" >
    </a>
    <a href="logout.php">
    <button class="button1">Logout</button>
    </a>    
    <a href="https://nmamit.nitte.edu.in/hostels.php">
    <button class="button1">Hostel Info</button>
    </a>
        
    <a href="https://nmamit.nitte.edu.in/index.php">
    <button class="button1">Home</button></a>
    </div><br>
    <hr style="height:2px;border:none;color:#333;background-color:#333;" /><br><br>
    
<?php
     
    $rid = $block = $rno = $rty = $mess = "----------";
    $sql = $link->prepare("SELECT * FROM room,student_room WHERE room.room_ssn=student_room.room_ssn and usn = ?");
    $sql->bind_param('s',$_SESSION["usn"]);
    $sql->execute();
    $row = $sql->get_result()->fetch_assoc();
    if($row && is_array($row)) 
    {
        $rid = $row["room_id"];
        $block = $row["block"];
        $rno = $row["room_no"];
        $rty = $row["type"];
    }
    
    if(!$row && !is_array($row))
    {
        if($_SESSION['type']=='g')
        {
            $sql1 = $link->prepare("SELECT * FROM room where type='Gents: 2-Seater' and occupied='n'");
            $sql1->execute();
            $row1 = $sql1->get_result()->fetch_assoc();
            
            $sql2 = $link->prepare("SELECT * FROM room where type='Gents: 2-Seater w/Bath' and occupied='n'");
            $sql2->execute();
            $row2 = $sql2->get_result()->fetch_assoc();
            
            $sql3 = $link->prepare("SELECT * FROM room where type='Gents: 3-Seater' and occupied='n'");
            $sql3->execute();
            $row3 = $sql3->get_result()->fetch_assoc();
        }
        else
        {
            $sql1 = $link->prepare("SELECT * FROM room where type='Ladies: 2-Seater w/Bath' and occupied='n'");
            $sql1->execute();
            $row1 = $sql1->get_result()->fetch_assoc();
            
            $sql2 = $link->prepare("SELECT * FROM room where type='Ladies: 3-Seater New Block' and occupied='n'");
            $sql2->execute();
            $row2 = $sql2->get_result()->fetch_assoc();
            
            $sql3 = $link->prepare("SELECT * FROM room where type='Ladies: 3-Seater Old Block' and occupied='n'");
            $sql3->execute();
            $row3 = $sql3->get_result()->fetch_assoc();
        }
        $sql1->close();
        $sql2->close();
        $sql3->close();
    }
    $sql->close();
?>
    
    <div class="box">
        <h2><b><u>Your Details:</u></b></h2>
        <table>
              <tr>
                <th>Name:</th>
                <td><?php echo "<span>{$_SESSION["name"]}</span>";?></td>
              </tr>
              <tr>
                <th>USN:</th>  
                <td><?php echo strtoupper("{$_SESSION["usn"]}");?></td>      
              </tr>  
              <tr>
                <th>Branch:</th>
                <td><?php echo "<span>{$_SESSION["branch"]}</span>";?></td>
              </tr>
              <tr>
                <th>Year:</th>
                <td><?php echo "<span>{$_SESSION["year"]}</span>";?></td>
              </tr>
        </table>
        <br><br>
    </div>
    <br><br><br>
    <div class="box">
        <h2><b><u>Hostel Details:</u></b></h2>
        <table>
              <tr>
                <th>Room ID:</th>
                <td><?php echo "<span>$rid</span>";?></td>
                  
              </tr>
              <tr>
                <th>Block:</th>  
                <td><?php echo "<span>$block</span>";?></td> 
                  
              </tr>  
              <tr>
                <th>Room No:</th>
                <td><?php echo "<span>$rno</span>";?></td>
                  
              </tr>
            <tr>
                <th>Room Type:</th>
                <td><?php echo "<span>$rty</span>";?></td>
                
              </tr>
        </table>
        <?php
        if(!$row && !is_array($row))
        {
            echo "<br>";
            echo "<b><span style='color:red'>Note: You haven't Booked a Room Yet!</span></b>";
        }
        ?>
        <br><br>
    </div>
    
    <div style="position:absolute;top:25%;left: 67%">
    <h2><u>Register for Hostels Here:</u></h2><br>

        <button onclick="document.getElementById('id01').style.display='block'" class="button1" id="register" style="position:absolute;left:50%;width:auto;transform: translate(-50%,-50%);" <?php if ($row && is_array($row)){ ?> disabled title="You've already booked a room!!!"<?php   } ?>>Book</button>
    </div>
        <a href="print.php"  target="_blank"><button class="button1" style="position:absolute;left:70%;top:75%;"<?php if (!$row && !is_array($row)){ ?> disabled title="You won't get a reciept unless you book a room!!!"<?php   } ?>>Print Reciept</button>
        </a>

        <div id="id01" class="modal">

          <form class="modal-content animate" action="payment.php" method="post" id="f01">
            <div class="imgcontainer">
              <span onclick="document.getElementById('id01').style.display='none'" class="close" title="Close Modal">&times;</span>
              </div>

            <div style="padding: 20px">
                <a href="https://drive.google.com/file/d/12O7hY9XIQfNuZGHQkjVYz61PXb6W6O9F/view?usp=sharing"><button class="dwnldbutton" style="">Download Hostel Fee (2020-2021)</button></a>
                
                 <h3><b><label for="roomtype">Choose From Available Room Type</label></b></h3>
                <select name="roomtype" required onchange="setfee(document.form, this.value);">
                    <option value="">Choose Type:</option>
                    <?php
                    if($_SESSION['type']=='g')
                    {
                        if($row1)
                        {
                    ?>
                    <option value="Gents: 2-Seater">Gents: 2-Seater</option>
                    <?php
                        }
                        if($row2)
                        {
                        ?>
                    <option value="Gents: 2-Seater w/Bath">Gents: 2-Seater w/Bath</option>
                        <?php
                        }
                        if($row3)
                        {
                        ?>
                    <option value="Gents: 3-Seater">Gents: 3-Seater</option>
                        <?php
                        }
                    }
                    else
                    {
                        if($row1)
                        { 
                    ?>        
                    <option value="Ladies: 2-Seater w/Bath">Ladies: 2-Seater w/Bath</option>
                    <?php
                        }
                        if($row2)
                        {
                    ?>        
                    <option value="Ladies: 3-Seater New Block">Ladies: 3-Seater New Block</option>
                    <?php
                        }
                        if($row3)
                        {
                    ?>        
                    <option value="Ladies: 3-Seater Old Block">Ladies: 3-Seater Old Block</option>
                    <?php
                        }
                    }
                    ?>
                  </select>
                <script>
                        var fee = {
                            'Gents: 2-Seater': 'Rs.66600',
                            'Gents: 2-Seater w/Bath': 'Rs.72600',
                            'Gents: 3-Seater': 'Rs.61600',
                            'Ladies: 2-Seater w/Bath': 'Rs 70600',
                            'Ladies: 3-Seater New Block': 'Rs.59600',
                            'Ladies: 3-Seater Old Block': 'Rs.53600'
                        };
                        var form = document.getElementById('f01');
                        form.elements.roomtype.onchange = function () {
                            var form = this.form;
                            form.elements.amt.value = fee[this.value];
                        };
                </script>
                <?php
                if(!$row1 && !$row2 &&!$row3)
                {
                    echo "<br><br>";
                    echo "<b><span style='color:red'>No Available Rooms!!! Please Contact College Office.</span></b>";
                }
            ?>
            <br>
                
            <h3><b>Amount</b></h3><input type="text" style="background-color: lightgoldenrodyellow;" name="amt" readonly>
                
                
            <br>
            <br>
            <button type="submit" class="button2">Pay</button>
            <button type="button" class="button2" onclick="document.getElementById('id01').style.display='none'" style="background-color: red; float: right"> Cancel</button>
 </div>

          </form>
        </div>

        <script>
        var modal = document.getElementById('id01');
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        </script>

    
</body>
</html>
