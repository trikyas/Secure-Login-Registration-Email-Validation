<?php
include('./includes/functions.php');
$currentPage = "New Password";
require_once('./includes/header.php');
include('./includes/db.php');
?>
    <div class="container">
        <div class="content">
            <h2 class="heading">New Password</h2>
            <?php
            if(isset($_GET['eid']) && isset($_GET['token']) && isset($_GET['exd'])) {
              $user_email = urldecode(base64_decode($_GET['eid']));
              $validation_key = urldecode(base64_decode($_GET['token']));
              $expire_date = urldecode(base64_decode($_GET['exd']));
              date_default_timezone_set('Australia/Sydney');
              $current_date = date("Y-m-d H:i:s");
              if($expire_date <= $current_date) {
                echo "<div class='notification'>Sorry link has <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>EXPIRED</span></div>";
              } else {
                $check = true;
                if(isset($_POST['submit'])) {
                  $user_pass = escape($_POST['new_password']);
                  $user_con_pass = escape($_POST['confirm_new_password']);
                  if($user_pass == $user_con_pass) {
                    $pattern_up = "/^.*(?=.{4,56})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).*$/";
                    if(!preg_match($pattern_up, $user_pass)) {
                      $errPass = "Passwords must have atleast:<br /> 4 characters<br /> 1 upper case<br /> 1 lower case<br /> 1 number";
                    }
                  } else {
                    $errPass = "Passwords do not <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>MATCH</span>";
                  }
                  if(!isset($errPass)) {
                    $query = "SELECT * FROM users WHERE user_email = '$user_email' AND validation_key = '$validation_key' AND is_active = 1";
                    $query_conn = mysqli_query($con, $query);
                    if(!$query_conn) {
                      die("Query Failed" . mysqli_error($con));
                    }
                    if(mysqli_num_rows($query_conn) == 1) {
                      $password = password_hash($user_pass, PASSWORD_ARGON2I, ['memory_cost' => 2048, 'time_cost' => 6, 'threads' => 4]);
                      $query1 = "UPDATE users SET user_password = '$password' WHERE validation_key = '$validation_key' AND user_email = '$user_email' AND is_active = 1";
                      $query_con1 = mysqli_query($con, $query1);
                      if(!$query_con1) {
                        die("Query Failed" . mysqli_error($con));
                      } else {
                        $query2 = "UPDATE users SET validation_key = 0 WHERE user_email = '$user_email' AND validation_key = '$validation_key' AND is_active = 1";
                        $query_con2 = mysqli_query($con, $query2);
                        if(!$query_con2) {
                          die("Query Failed" . mysqli_error($con));
                        }
                        echo "<div class='notification'>New password <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>CREATED</span></div>";
                        header("Refresh: 3; url='login.php'");
                      }
                    } else {
                      echo "<div class='notification'>This link is <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>INVALID</span></div>";
                    }
                  }
                }
              }
            } else {
              echo "<div class='notification'>Something went terribly <span style='background-image: linear-gradient(to right bottom, rgba(187, 5, 75, 0.952), rgb(255, 0, 98)); color:#fff;padding: 10px 15px; font-family: Helvetica;'>WRONG</span></div>";
            }
            if(isset($errPass)) {
              echo "<div class='notification'>{$errPass}</div>";
            }
            ?>
            <form action="" method="POST">
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="New password" name="new_password" required <?php echo !isset($check)?"disabled":"";?> />
                </div>
                <div class="input-box">
                    <input type="password" class="input-control" placeholder="Confirm new password" name="confirm_new_password" required <?php echo !isset($check)?"disabled":"";?> />
                </div>
                <div class="input-box">
                    <input type="submit" class="input-submit" value="SUBMIT" name="submit" <?php echo !isset($check)?"disabled":"";?> />
                </div>
            </form>

        </div>
    </div>
<?php include('./includes/footer.php'); ?>
