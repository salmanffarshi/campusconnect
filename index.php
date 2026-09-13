<?php
require_once "controllers/authCheck.php";

if (isLoggedIn())
{
    $user = getLoggedUser();

    if ($user != null && $user["status"] == "active")
    {
        header("Location: /campusconnect" . dashboardForRole($user["role"]));
        exit();
    }

    logoutUser();
}

header("Location: /campusconnect/views/login.php");
exit();
?>
