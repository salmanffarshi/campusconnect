<?php
require_once "../controllers/authCheck.php";
logoutUser();
header("Location: /campusconnect/views/login.php");
exit();
?>