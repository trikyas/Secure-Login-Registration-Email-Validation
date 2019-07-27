<?php
ob_start();
session_start();
$currentPage = "Home";

include('./includes/db.php');
include('./includes/functions.php');
// require_once('./includes/header.php');
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
<div class='front-end'>
  <?php
  if(isset($_SESSION['login'])) {
    echo "<div class='notification'>You have Logged In: <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>Successfully</span></div><br /><table width='160' class='button' style='margin: 2.5vh auto;'><tr><td class='btn-read-online' style='text-align: center; background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); padding: 5px 10px; border-radius: 5px; box-shadow:0 4px 0 1px #fff, 0 -2px 0 1px darkred;'><a href='logout.php' style='color:#fff; font-size: 18px; letter-spacing: 1px; text-decoration: none; font-family: Helvetica;'>Logout</a><br /></td></tr></table>";
  } else if(isAlreadyLoggedIn()) {
    echo "<div class='notification'>Welcome back <h2>{$_SESSION['name']}</h2> You are  <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>logged in</span></div>";
  }
  else {
    echo "<div class='notification'>Log In: <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>First</span></div><br /><table width='160' class='button' style='margin: 2.5vh auto;'><tr><td class='btn-read-online' style='text-align: center; background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); padding: 5px 10px; border-radius: 5px; box-shadow:0 4px 0 1px #fff, 0 -2px 0 1px darkred;'><a href='login.php' style='color:#fff; font-size: 18px; letter-spacing: 1px; text-decoration: none; font-family: Helvetica;'>Login Now</a><br /></td></tr></table>";
  }
  ?>
  
  </div>
    <?php require_once('./includes/footer.php'); ?>
