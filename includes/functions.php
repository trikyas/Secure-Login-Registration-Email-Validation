<?php
define('header', TRUE);
define('footer', TRUE);
// sql prevention
function escape($string) {
  global $con;
  return mysqli_real_escape_string($con, $string);
}

// create function for Randomtoken
function getToken($len) {
  $rand_str = md5(uniqid(mt_rand(), true));//32 char string
  $base64_encode = base64_encode($rand_str);
  $modified_base64_encode = str_replace(array('+','/','='), array('-','_',''), $base64_encode);
  $token = substr($modified_base64_encode, 0, $len);
  return $token;
}
// Remember Me ?
function selectUserByToken ($token) {
  global $con;
  $query = "SELECT user_name FROM remember_me WHERE selector = '$token' AND is_expired = 0";
  $query_con = mysqli_query($con, $query);
  if(!$query_con) {
    die("Query Failed" . mysqli_error($con));
  }
  $result = mysqli_fetch_assoc($query_con);
  $user_name = $result['user_name'];
  $query1 = "SELECT * FROM users WHERE user_name = '$user_name'";
  $query_con1 = mysqli_query($con, $query1);
  if(!$query_con1) {
    die("Query Failed" . mysqli_error($con));
  }
  $result1 = mysqli_fetch_assoc($query_con1);
  return $result['first_name'] . " " . $result['last_name'];
}
function isAlreadyLoggedIn() {
  global $con;
  date_default_timezone_set("Australia/Sydney");
  $current_date = date("Y-m-d H:i:s");
  if(isset($_COOKIE['_uvc_'])) {
    $selector = escape(base64_decode($_COOKIE['_uvc_']));
    $query = "SELECT * FROM remember_me WHERE selector = '$selector' AND is_expired = 0";
    $query_con = mysqli_query($con, $query);
    if(!$query_con) {
      die("Query Failed" . mysqli_error($con) );
    }
    $result = mysqli_fetch_assoc($query_con);
    if(mysqli_num_rows($query_con) == 1) {
      $expire_date = $result['expire_date'];
      if($expire_date >= $current_date) {
        $name = selectUserByToken ($selector);
        $_SESSION['name'] = $name;
        return true;
      }
    }
  }
}






















?>
