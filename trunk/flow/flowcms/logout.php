<?php
include "../inc/global.inc.php";
unset($_SESSION['flow_uid']);
unset($_SESSION['flow_username']);
unset($_SESSION['flow_password']);
jump('login.php');
?>