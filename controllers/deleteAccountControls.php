<?php

require_once "authCheck.php";
require_once "../models/usersModel.php";

$user = checkLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    if ($user["role"] == "admin")
    {
        header("Location: /campusconnect/views/account/profile.php?deleteErr=" . urlencode("Admin accounts cannot be deleted."));
        exit();
    }

    $password = $_POST["password"];

    if (empty($password) || !password_verify($password, $user["password"]))
    {
        header("Location: /campusconnect/views/account/profile.php?deleteErr=" . urlencode("Password is not correct. Account was not deleted."));
        exit();
    }

    deactivateUser($user["user_id"]);
    logoutUser();

    header("Location: /campusconnect/views/login.php?generalMessage=" . urlencode("Your account has been deleted."));
    exit();
}

?>
