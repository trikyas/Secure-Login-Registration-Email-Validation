<?php
ob_start();
session_start();
include('define.php');
$currentPage = "";

if(isset($_SESSION['login']) || isset($_COOKIE['_ucv_'])) {
  header("Location: index.php");
}
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
  <?php
  if(isAlreadyLoggedIn()) {
    header("Location: index.php");
    exist;
  }

      include('vendor/autoload.php');

      use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\Exception;

      $mail = new PHPMailer();

      $mail->isSMTP();
      $mail->SMTPAuth = true;
      $mail->Host = "smtp.gmail.com";
      $mail->Port = "465";
      $mail->SMTPSecure = "ssl";

      $mail->Username = "Your_Gmail_account@gmail.com";
      $mail->Password = "Your_Gmail_Password";

      $mail->setFrom("Sent_From_Address@email_client.com");
      $mail->addReplyTo("no-reply_trikyas@chad.com");

      $mail->isHTML();

?>
