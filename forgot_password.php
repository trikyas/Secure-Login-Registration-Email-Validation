<?php
include('./includes/functions.php');

$currentPage = "Forgot Password";

require_once('./includes/header.php');
?>
    <div class="container">
        <div class="content">
            <h2 class="heading">Password Recovery</h2>

            <?php
            include('./includes/db.php');
            if(isset($_POST['password_recovery'])) {
              $user_name = escape($_POST['user_name']);
              $user_email = escape($_POST['user_email']);
              $query = "SELECT * FROM users WHERE user_name = '$user_name' AND user_email = '$user_email' AND is_active = 1";
              $query_conn = mysqli_query($con, $query);
              if(!$query_conn) {
                die("Query Failed" . mysqli_error($con));
              }
              if(mysqli_num_rows($query_conn) == 1) {
                if(!isset($_COOKIE['_unp_'])) {

                    $user_name = $_POST['user_name'];
                    $user_email = $_POST['user_email'];
                    date_default_timezone_set('Australia/Sydney');
                    // email resend Confirmation
                    $mail->addAddress($_POST['user_email']);
                    $email = $_POST['user_email'];
                    $token = getToken(32);// 32 char length
                    $encode_token = base64_encode(urlencode($token));
                    $email = base64_encode(urlencode($_POST['user_email']));
                     // time() === now , + 60 seconds multiplied by 20 === 20 minutes
                    $expire_date = date("Y-m-d H:i:s", time() + 60 * 20);
                    $expire_date = base64_encode(urlencode($expire_date));

                    $query = "UPDATE users SET validation_key = '$token' WHERE user_name = '$user_name' AND user_email = '$user_email' AND is_active = 1";
                    $query_con = mysqli_query($con, $query);
                    if(!$query_con) {
                      die("Query Failed" . mysqli_error($con));
                    } else {
                      $mail->Subject = "Request to change PASSWORD";
                      $mail->Body = "<h2>Click the link to reset password.</h2><table width='160' class='button' style='margin: 0px 0px 10px 30px;'><tr><td class='btn-read-online' style='text-align: center; background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); padding: 10px 15px; border-radius: 5px;'><a href='http://127.0.0.1:8888/registration/new_password.php?eid={$email}&token={$encode_token}&exd={$expire_date}' style='color:#fff; font-size: 18px; letter-spacing: 1px; text-decoration: none; font-family: Helvetica;'>RESET PASSWORD</a><br /></td></tr></table><p>This link is ONLY valid for 20 minutes</p><p>If you did not request a change of password. Please ignore this email.</p>";
                      if($mail->send()) {
                        setcookie('_unp_', getToken(16), time() + 60 *20, '', '', '', true);
                        echo "<div class='notification'>RESET PASSWORD link has been sent. Please check <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>Your Email</span></div>";
                      }
                    }
                } else {
                  echo "<div class='notification'>Please wait 20 minutes to Resend another <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>EMAIL</span></div>";
                }
              } else {
                echo "<div class='notification'>You're not here <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>FAILED REQUEST</span></div>";
              }
            }
            ?>
            <form action="" method="POST">
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Username" name="user_name" required>
                </div>
                <div class="input-box">
                    <input type="email" class="input-control" placeholder="Email address" name="user_email" required>
                </div>
                <div class="input-box rm-box">
                    <a href="login.php" class="forgot-password">LOGIN?</a>
                </div>
                <div class="input-box">
                    <input type="submit" class="input-submit" value="RECOVER PASSWORD" name="password_recovery" required>
                </div>
            </form>
        </div>
    </div>
<?php require_once('./includes/footer.php'); ?>
