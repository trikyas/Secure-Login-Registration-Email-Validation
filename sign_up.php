<?php $currentPage = "Sign UP"; ?>
<?php include('./includes/functions.php'); ?>
<?php require_once("./includes/header.php"); ?>
    <div class="container">
        <div class="content">
            <h2 class="heading">Sign Up</h2>
            <?php
            //google recaptcha
            $public_key = "6LeCm64UAAAAAGMNQ9_2IIUjGHVdM3cV_gHoG_Ot";
            $private_key = "6LeCm64UAAAAAK7sEV_2BeePPaaK0MjFJmuR2V7O";
            $url = "https://www.google.com/recaptcha/api/siteverify";
            if(isset($_POST['sign-up'])) {
              require('./includes/db.php');
              //Google recaptcha
              $response_key = $_POST['g-recaptcha-response'];
              //https://www.google.com/recaptcha/api/siteverify?secret=$private_key&response=$response_key&remoteip=currentScriptIpAddress
              $response = file_get_contents($url . "?secret=" . $private_key . "&response=" . $response_key . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
              $response = json_decode($response);
              if(!($response->success == 1)) {
                $errCaptcha = "Wrong captcha";
              }
              $first_name = escape($_POST['first_name']);
              $last_name = escape($_POST['last_name']);
              $user_name = escape($_POST['user_name']);
              $user_email = escape($_POST['user_email']);
              $user_pass = escape($_POST['user_password']);
              $user_con_pass = escape($_POST['user_confirm_password']);
              //first name validation
              $pattern_fn = "/^[a-zA-Z \-]{2,38}$/";
              if(!preg_match($pattern_fn, $first_name)) {
                  $errFn = "2 or more characters, ONLY letters, spaces and - are allowed";
              }
              //last name validation
              $pattern_ln = "/^[a-zA-Z \-]{2,38}$/";
              if(!preg_match($pattern_ln, $last_name)) {
                  $errLn = "2 or more characters, ONLY letters, spaces and - are allowed";
              }
              //user name validation
              //at lest 3 character, letter, numeber and underscore allowed
              $pattern_un = "/^[a-zA-Z0-9_]{3,16}$/";
              if(!preg_match($pattern_un, $user_name)) {
                  $errUn = "3 or more characters, letters, numbers and _ are allowed";
              } else {
                $query = "SELECT * FROM users WHERE user_name = '$user_name' ";
                $query_conn = mysqli_query($con, $query);
                if(!$query_conn) {
                  die("Query Failed" . mysqli_error($con));
                }
                $count = mysqli_num_rows($query_conn);
                if($count == 1) {
                  $errUn = "Username is already taken";
                }
              }
              //email validation
              $pattern_ue = "/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/i";
              if(!preg_match($pattern_ue, $user_email)) {
                $errUe = "Invalid email";
              } else {
                $query = "SELECT * FROM users WHERE user_email = '$user_email' ";
                $query_conn = mysqli_query($con, $query);
                if(!$query_conn) {
                  die("Query Failed" . mysqli_error($con));
                }
                $count = mysqli_num_rows($query_conn);
                if($count == 1) {
                  $errUe = "Email is already used. Login";
                }
              }
              if($user_pass == $user_con_pass) {
                //password validation
                $pattern_up = "/^.*(?=.{4,56})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/";
                if(!preg_match($pattern_up, $user_pass)) {
                  $errPass = "4 or more characters, 1 upper case, 1 lower case letter and 1 number<br />Ex4mple";
                }
              } else {
                $errPass = "Passwords do not MATCH";
              }
              if(!isset($errFn) && !isset($errLn) && !isset($errUn) && !isset($errUe) && !isset($errPass) && !isset($errCaptcha)) {
                $hash = password_hash($user_pass, PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 6, 'threads' => 4]);
                date_default_timezone_set('Australia/Sydney');
                $date = date("Y-m-d H:i:s");
                // email Confirm
                $mail->addAddress($_POST['user_email']);
                $email = $_POST['user_email'];
                $token = getToken(32);// 32 char length
                $email = base64_encode(urlencode($_POST['user_email']));
                 // time() === now , + 60 seconds multiplied by 20 === 20 minutes
                $expire_date = date("Y-m-d H:i:s", time() + 60 * 20);
                $expire_date = base64_encode(urlencode($expire_date));
                $mail->Subject = "Verify your Email";
                $mail->Body = "<h2>Thank you for Signing up.</h2><table width='160' class='button' style='margin: 0px 0px 10px 30px;'><tr><td class='btn-read-online' style='text-align: center; background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); padding: 10px 15px; border-radius: 5px;'><a href='http://127.0.0.1:8888/registration/activation.php?eid={$email}&token={$token}&exd={$expire_date}' style='color:#fff; font-size: 18px; letter-spacing: 1px; text-decoration: none; font-family: Helvetica;'>ACTIVATE ACCOUNT</a><br /></td></tr></table><p>This link is ONLY valid for 20 minutes</p>";
                if($mail->send()) {
                  $query = "INSERT INTO users (first_name, last_name, user_name, user_email, user_password, validation_key, registration_date) VALUES ('$first_name', '$last_name', '$user_name', '$user_email', '$hash', '$token', '$date')";
                  $query_conn = mysqli_query($con, $query);
                  if(!$query_conn) {
                      die("Query failed" . mysqli_error($con));
                  } else {
                     $success = "<div class='notification'>Sign up successful. Check your email for <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'> activation link</span><br />5 seconds to redirect.</div>";
                     header('Refresh: 5; URL=login.php');
                     unset($first_name);
                     unset($last_name);
                     unset($user_name);
                     unset($user_email);
                  }
                }
              }
            }
            ?>
            <?php echo isset($success)?"<span class='error'>{$success}</span>":""; ?>
            <form action="sign_up.php" method="POST">
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="First name" name="first_name" autocomplete="off" value="<?php echo isset($first_name)?$first_name:""; ?>" />
                    <?php echo isset($errFn)?"<span class='error'>{$errFn}</span>":""; ?>
                </div>
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Last name" name="last_name" autocomplete="off" value="<?php echo isset($last_name)?$last_name:""; ?>" />
                    <?php echo isset($errLn)?"<span class='error'>{$errLn}</span>":""; ?>
                </div>
                <div class="input-box">
                    <input type="text" class="input-control" placeholder="Username" name="user_name" autocomplete="off" value="<?php echo isset($user_name)?$user_name:""; ?>" />
                    <?php echo isset($errUn)?"<span class='error'>{$errUn}</span>":""; ?>
                </div>
                <div class="input-box">
                    <input type="email" class="input-control" placeholder="Email address" name="user_email" autocomplete="off" value="<?php echo isset($user_email)?$user_email:""; ?>" />
                    <?php echo isset($errUe)?"<span class='error'>{$errUe}</span>":""; ?>
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Enter password" name="user_password" autocomplete="off">
                    <?php echo isset($errPass)?"<span class='error'>{$errPass}</span>":""; ?>
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Confirm password" name="user_confirm_password" autocomplete="off">
                    <?php echo isset($errPass)?"<span class='error'>{$errPass}</span>":""; ?>
                </div>
                <div class="g-recaptcha" data-sitekey="<?php echo $public_key; ?>"></div>
                <?php echo isset($errCaptcha)?"<span class='error'>{$errCaptcha}</span>":""; ?>
                <div class="input-box">
                    <input type="submit" class="input-submit" value="SIGN UP" name="sign-up">
                </div>
                <div class="sign-up-cta"><span>Already have an account?</span> <a href="login.php">Login here</a></div>
            </form>

        </div>
    </div>

<?php require_once("./includes/footer.php"); ?>
