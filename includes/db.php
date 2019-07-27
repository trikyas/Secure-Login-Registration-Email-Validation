<?php
define("DB_SERVER", "localhost");
define("DB_USER", "root");
define("DB_PASS", "root");
define("DB_NAME", "registration");
$con = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
if(mysqli_connect_errno()) {
die("Database Failed: " . mysqli_connect_error() . " (" . mysqli_connect_errno() .")" );
}

mysqli_set_charset($con, 'utf8');
?>
