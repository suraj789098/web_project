<?php
session_start();
 
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: main.php");
    exit;
} 
require_once "config.php";

$usn = $dob = "";
$usn_err = $dob_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $usn = trim($_POST["usn"]);
    $dob = trim($_POST["dob"]);
    
    $sql = $link->prepare("SELECT * FROM student WHERE usn = ?");
    $sql->bind_param('s',$usn);
    $sql->execute();
    $row = $sql->get_result()->fetch_assoc();
    if($row && is_array($row))
    {
        $hash = password_hash($row['dob'],PASSWORD_DEFAULT);
        if(password_verify($dob, $hash))
        {
            $_SESSION["userid"] = $row['id'];
            $_SESSION["usn"] = $row['usn'];
            $_SESSION["loggedin"] = true;
            $_SESSION["name"] = $row['name'];
            $_SESSION["branch"] = $row['branch'];
            $_SESSION["year"] = $row['year'];
            $_SESSION["type"]= $row['hosteltype'];
            header("location: main.php");
            exit;
        }
        else
        {
            $dob_err = "The Password is Incorrect";
        }
    }
    else
    {
        $usn_err = "The USN is Invalid";
    }
    $sql->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hostel Management - NMAMIT</title>
    <link rel="icon" href="Images/icon.png" type="image/png">
    <link rel="stylesheet" href="Style.css">
    <script>
        window.addEventListener('scroll', function () {document.body.classList[window.scrollY > 20 ? 'add' : 'remove']('scrolled'); });
    </script>
</head>

<body style="background-color:bisque">
    <!--Header-->
    <div id="hd" class="head" >
    <a href="https://nmamit.nitte.edu.in/index.php" target="_blank" title="Nitte Mahalinga Adyanthaya Memorial Institute of Technology">
    <img src="Images/nitte-eng.png" style="margin-left: 20px" width="32%" height="auto" >
    </a>
    
    <button class="button1" onclick="document.getElementById('m01').style.display='block'">Login</button>
        
    <a href="https://nmamit.nitte.edu.in/hostels.php">
    <button class="button1">Hostel Info</button>
    </a>
        
    <a href="https://nmamit.nitte.edu.in/index.php">
    <button class="button1">Home</button></a><br><br>
        <hr style="height:2px;border:none;color:#333;background-color:#333;" />
    </div>
    
    <!--Modal Login-->
    <div id="m01" class="modal">
    <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            echo "<script>var x=document.getElementById('m01');x.style.display='block';</script>";
        }
    ?>    
    <form class="modal-content animate" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="imgcontainer">
        <span onclick="document.getElementById('m01').style.display='none'" class="close" title="Close">&times;</span>
        <img src="Images/nitte.jpg" width="35%" alt="NITTE" style="margin: 15px 0px">
        </div>
        
        <div style="padding: 20px">
            <h3><b>USN</b></h3> <input type="text" name="usn" placeholder="Enter USN" required><br>
            <?php
                if(!empty($usn_err))
                {
                    echo "<b><span style='color:red'>USN is Invalid</span></b>";
                }
            ?>
            <h3><b>DoB</b></h3><input type="password" name="dob" placeholder="yyyymmdd" required><br>
            <?php
                if(!empty($dob_err))
                {
                    echo "<b><span style='color:red'>DOB is Incorrect</span></b>";
                }
            ?>
            <br>
            <button type="submit" class="button2">Login</button>
            <button type="button" class="button2" onclick="document.getElementById('m01').style.display='none'" style="background-color: red; float: right"> Cancel</button>
        </div>
    </form>  
    </div>
    <script>
        var modal = document.getElementById('m01');
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    
    <!--BODY-->

    <br>
    <div style="padding-left: 20px">
    <div class="box" style="width:17%;padding-top: 0px;"><h1>Hostel Booking</h1></div><br><br>
        <div class="column">
        <div class="polaroid" style="width:40%">
    <img src="Images/main.jpg" alt="Main Hostel Block">
  <div style="text-align: center;padding: 2px 20px;">
      <p style="font-size:20px"><b>Main Hostel Block</b></p>
  </div>
</div>   
         <div class="polaroid" style="position:absolute;top:35%;left:55%;z-index:0">
    <img src="Images/mess.jpg" alt="Hostel Mess">
  <div style="text-align: center;padding: 2px 20px;">
      <p style="font-size:20px"><b>Hostel Mess</b></p>
  </div>
</div>
         <div class="polaroid">
    <img src="Images/girls.jpg" alt="Ladies Hostel">
  <div style="text-align: center;padding: 2px 20px;">
      <p style="font-size:20px"><b>Ladies Hostel</b></p>
  </div>
</div>
    <div class="polaroid" style="position:absolute;top:95%;left:55%;z-index:0">
    <img src="Images/boys.jpg" alt="Gents Hostel">
  <div style="text-align: center;padding: 2px 20px;">
      <p style="font-size:20px"><b>Gents Hostel</b></p>
  </div>
</div> 
        </div>
        
    <br><br>
     <div class="box" style="position:relative;left:20%">  <h2>Contact Us</h2>
         <h3>
        <p>Developed By :</p>
        <p>Vishnu A C  <br>
          Email :<a href="mailto:4nm18cs214@nmamit.in">4nm18cs214@nmamit.in</a></p>
        <p>Suraj Nayak <br>
            Email : <a href="mailto:4nm18cs197@nmamit.in">4nm18cs197@nmamit.in</a></p>
         </h3>
        </div>
    
    </div>
</body>
</html>
