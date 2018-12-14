<?php
require "functions/init.php";
$email = $_SESSION['email'];
$time = time();
$lastseen = strftime("%B-%d-%Y %H:%M:%S",$time);
$sql = "UPDATE users SET last_seen = '$lastseen' WHERE id ='$id' OR email = '$email'";
$result = query($sql);
confirm($result);
session_destroy();

if (isset($_COOKIE["email"])) {
    unset($_COOKIE["email"]);
    setcookie("email",'',time()-86400*6);
}

redirect("login.php");