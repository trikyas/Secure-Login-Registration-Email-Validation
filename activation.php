<?php
$currentPage = "Activation";
require('includes/db.php');
require('includes/functions.php');
require_once("./includes/header.php");
?>

    <div class="container">
        <div class="content">
            <h2 class="heading">Sign Up Complete</h2>
<?php
   if(isset($_GET['eid']) && isset($_GET['token']) && isset($_GET['exd'])) {
     $validation_key = $_GET['token'];
     $email = urldecode(base64_decode($_GET['eid']));
     $expire = urldecode(base64_decode($_GET['exd']));
     echo "<div class='notification' style='text-align: center;background-color: #999;border-left: 6px solid rgb(255, 0, 98);color: #fff;padding: 1rem 2rem;margin: 1rem 0;'>Link expires at: " . $expire . "</div>" ;
     date_default_timezone_set('Australia/Sydney');
     $current_date = date("Y-m-d H:i:s");
     if($current_date >= $expire) {
       echo "<div class='notification' style='text-align: center;background-color: #999;border-left: 6px solid rgb(255, 0, 98);color: #fff;padding: 1rem 2rem;margin: 1rem 0;'>Link has <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>Expired</span></div>";
       // echo "Link has expired";
     } else {
       $query1 = "SELECT * FROM users WHERE user_email = '$email' AND validation_key = '$validation_key' AND is_active = 1";
       $query_con1 = mysqli_query($con, $query1);
       if(!$query_con1) {
         die("Query Failed" . mysqli_error($con));
       }
       $count = mysqli_num_rows($query_con1);
       if($count == 1) {
         echo "<div class='notification' style='text-align: center;background-color: #999;border-left: 6px solid rgb(255, 0, 98);color: #fff;padding: 1rem 2rem;margin: 1rem 0;'>Email has been <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>Activated</span></div>";
         // echo "Email is already Activated";
       } else {
         $query = "UPDATE users SET is_active = 1 WHERE user_email = '$email' AND validation_key = '$validation_key'";
         $query_conn = mysqli_query( $con, $query);
         if(!$query_conn) {
           die("Query failed" . mysqli_error());
         }
         if($query_conn) {
           echo "<div class='notification' style='text-align: center;background-color: #999;border-left: 6px solid rgb(255, 0, 98);color: #fff;padding: 1rem 2rem;margin: 1rem 0;'>Account <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>Activated</span></div>";
           // echo "Validated";
         }
       }
     }
   }
?>
<form action="activation.php" method="POST">
  <?php
  if(isset($_POST['login'])) {
    header('Location: login.php');
  }
  ?>
    <div class="input-box">
        <input type="submit" class="input-submit" value="LOGIN" name="login">
    </div>
</form>
