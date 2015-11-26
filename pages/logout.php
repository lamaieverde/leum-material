<?php
$time = time() - 31536000;
setcookie("name", "", $time, "/");
setcookie("email", "", $time, "/");
header("location:../index.php");
exit;
?>