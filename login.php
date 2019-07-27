<?php
$currentPage = "Login";
include('./includes/functions.php');
require_once('./includes/header.php');

?>
    <div class="container">
        <div class="content">
            <h2 class="heading">Login</h2>

            <?php

                require('./includes/db.php');
                //google recaptcha
                $public_key = "6LeCm64UAAAAAGMNQ9_2IIUjGHVdM3cV_gHoG_Ot";
                $private_key = "6LeCm64UAAAAAK7sEV_2BeePPaaK0MjFJmuR2V7O";
                $url = "https://www.google.com/recaptcha/api/siteverify";

            if(isset($_POST['resend'])) {
              if(!isset($_COOKIE['_utt_'])) {

                  $user_name = $_POST['user_name'];
                  $user_email = $_POST['user_email'];
                  date_default_timezone_set('Australia/Sydney');
                  // email resend Confirmation
                  $mail->addAddress($_POST['user_email']);
                  $email = $_POST['user_email'];
                  $token = getToken(32);// 32 char length
                  $email = base64_encode(urlencode($_POST['user_email']));
                   // time() === now , + 60 seconds multiplied by 20 === 20 minutes
                  $expire_date = date("Y-m-d H:i:s", time() + 60 * 20);
                  $expire_date = base64_encode(urlencode($expire_date));

                  $query = "UPDATE users SET validation_key = '$token' WHERE user_name = '$user_name' AND user_email = '$user_email' AND is_active = 0";
                  $query_con = mysqli_query($con, $query);
                  if(!$query_con) {
                    die("Query Failed" . mysqli_error($con));
                  } else {
                    $mail->Subject = "Verify your Email";
                    $mail->Body = "<h2>Click the link to activate account.</h2><table width='160' class='button' style='margin: 0px 0px 10px 30px;'><tr><td class='btn-read-online' style='text-align: center; background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); padding: 10px 15px; border-radius: 5px;'><a href='http://127.0.0.1:8888/registration/activation.php?eid={$email}&token={$token}&&exd={$expire_date}' style='color:#fff; font-size: 18px; letter-spacing: 1px; text-decoration: none; font-family: Helvetica;'>Activate Account</a><br /></td></tr></table><p>This link is ONLY valid for 20 minutes</p>";
                    if($mail->send()) {
                      setcookie('_utt_', getToken(16), time() + 60 *20, '', '', '', true);
                      echo "<div class='notification'>Activation link has been sent. Please check <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>Your Email</span></div>";
                    }
                  }
              } else {
                echo "<div class='notification'>Please wait 20 minutes to Resend another <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>Activation email</span></div>";
              }
            }
            $isAuthenticated = false;
            if(isset($_POST['login'])) {
            //Google recaptcha
            $response_key = $_POST['g-recaptcha-response'];

            //https://www.google.com/recaptcha/api/siteverify?secret=$private_key&response=$response_key&remoteip=currentScriptIpAddress
            $response = file_get_contents($url . "?secret=" . $private_key . "&response=" . $response_key . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
            $response = json_decode($response);

            if(!($response->success == 1)) {
                $errCaptcha = "Wrong captcha";
            }

              $user_name = escape($_POST['user_name']);
              $user_email = escape($_POST['user_email']);
              $user_pass = escape($_POST['user_password']);

              $query = "SELECT * FROM users WHERE user_name = '$user_name' AND user_email = '$user_email'";
              $query_con = mysqli_query($con, $query);
              if(!$query_con) {
                die("Query Failed" . mysqli_error($con));
              }
              $result = mysqli_fetch_assoc($query_con);
              // verify password
              if(password_verify($user_pass, $result['user_password'])) {
              if($result['is_active'] == 1) {
                if(!isset($errCaptcha)) {

                  $isAuthenticated = true;
                  echo "<div class='notification'>Logged In: <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>Successfully</span></div>";
                }
              } else {
                if(!isset($errCaptcha)) {
                  echo "<div class='notification'>Please verify email<form action='login.php' method='post'><input type='text' value={$user_name} name='user_name' hidden /><input type='email' value={$user_email} name='user_email'  hidden/><input style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;' type='submit' class='resend' name='resend' value='click this link to Resend Email' /></form></div>";
                }
              }
            } else {
                echo "<div class='notification'>Wrong username or <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>PASSWORD</span></div>";
              }
            }
            if($isAuthenticated) {
              $_SESSION['login'] = 'success';
              // header('Refresh: 3; url=index.php');
              if(!empty($_POST['remember-me'])) {
                // echo "<div class='notification'>Remember <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>ME</span></div>";
                $selector = getToken(32);
                $encoded_selector = base64_encode($selector);
                setcookie('_ucv_', $encoded_selector, time() + 60 * 60 * 24 * 2, '', '', '', true);
                date_default_timezone_set('Australia/Sydney');
                $expire = date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 2);

                $query_rm = "INSERT INTO remember_me (user_name, selector, expire_date, is_expired) VALUES ('$user_name', '$selector', '$expire', 0)";
                $query_con_rm = mysqli_query($con, $query_rm);
                if(!$query_con_rm) {
                  die("Query Failed" . mysqli_error($con));
                }
              }
              $_SESSION['login'] = 'success';
              header("Refresh: 3; url=index.php");
            }
            ?>
            <form action="login.php" method="post">
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Username" name="user_name" required>
                </div>
                <div class="input-box">
                    <input type="email" class="input-control" placeholder="Email address" name="user_email" required>
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Enter password" name="user_password" required>
                </div>
                <div class="input-box rm-box">
                    <div>
                        <input type="checkbox" id="remember-me" class="remember-me" name="remember-me">
                        <label for="remember-me">Remember me</label>
                    </div>
                    <a href="forgot_password.php" class="forgot-password">Forgot password?</a>
                </div>
                <div class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>"></div>
                <?php echo isset($errCaptcha)?"<span class='error'>{$errCaptcha}</span>":""; ?>
                <div class="input-box">
                    <input type="submit" class="input-submit" value="LOGIN" name="login">
                </div>
                <div class="login-cta"><span>Don't have an account?</span> <a href="sign_up.php">Sign up here</a></div>
            </form>

        </div>
    </div>
<?php require_once('./includes/footer.php'); ?>
