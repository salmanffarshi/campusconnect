<?php

require_once "authCheck.php";
require_once "../models/usersModel.php";

$admin = checkRole("admin");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $userId = (int) $_POST["userId"];
    $status = $_POST["status"];

    if ($status != "active" && $status != "inactive")
    {
        header("Location: /campusconnect/views/admin/users.php?generalErr=".urlencode("Status must be active or inactive."));
        exit();
    }

    // The admin must not lock themselves out.
    if ($userId == $admin["user_id"])
    {
        header("Location: /campusconnect/views/admin/users.php?generalErr=".urlencode("You cannot change the status of your own account."));
        exit();
    }

    $person = findUserById($userId);

    if ($person == null)
    {
        header("Location: /campusconnect/views/admin/users.php?generalErr=".urlencode("User not found."));
        exit();
    }

    setUserStatus($userId, $status);

    header("Location: /campusconnect/views/admin/users.php?generalMessage=".urlencode($person["name"]." is now ".$status."."));
    exit();
}

?>
