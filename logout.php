<?php
$currentPage = "SEE YOU NEXT TIME";
include('./includes/db.php');
include('./includes/functions.php');
ob_start();
session_start();
if(isset($_COOKIE['_ucv_'])) {
  global $con;
  $selector = escape(base64_decode($_COOKIE['_ucv_']));
  $query = "UPDATE remember_me SET is_expired ='-1' WHERE selector ='$selector' AND is_expired =0";

  $query_con = mysqli_query($con, $query);
  if(!$query_con) {
    die("Query Failed" . mysqli_error($con));
  }
  setcookie('_ucv_', '', time() - 60 * 60);
}
if(isset($_SESSION['login'])) {
  session_destroy();
  unset($_SESSION['login']);
  unset($_SESSION['name']);
  header('Refresh:5; url=login.php');
}

header('Refresh:5; url=login.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $currentPage; ?></title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
<div class="container">
    <div class="content">
        <h2 class="heading">See you next time</h2>
        <?php
        echo "<div class='notification'>You have been <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>LOGGED OUT</span></div>";
        ?>
            <div>
              <p style="text-align:center;">
                Please wait while we secure the way...
              </p>
            </div>
    </div>
</div>
