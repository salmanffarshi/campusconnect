<?php

require_once "authCheck.php";
require_once "../models/usersModel.php";

$user = checkLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $currentPass = $_POST["currentPass"];
    $newPass = $_POST["newPass"];
    $confirmPass = $_POST["confirmPass"];

    $hasErr = false;
    $currentPassErr = "";
    $newPassErr = "";
    $confirmPassErr = "";

    if (empty($currentPass))
    {
        $currentPassErr = "Please write your current password.";
        $hasErr = true;
    }
    else if (!password_verify($currentPass, $user["password"]))
    {
        $currentPassErr = "Your current password is not correct.";
        $hasErr = true;
    }

    if (empty($newPass))
    {
        $newPassErr = "New password cannot be empty.";
        $hasErr = true;
    }
    else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $newPass))
    {
        $newPassErr = "Must be at least 8 characters long and include an uppercase letter, lowercase letter, number, and special character.";
        $hasErr = true;
    }
    else if ($newPass == $currentPass)
    {
        $newPassErr = "The new password must be different from the current one.";
        $hasErr = true;
    }

    if ($newPass != $confirmPass)
    {
        $confirmPassErr = "Confirm password and new password didn't match.";
        $hasErr = true;
    }

    
    if ($hasErr)
    {
        $url = "Location: /campusconnect/views/account/changePassword.php?currentPassErr=".urlencode($currentPassErr)."&newPassErr=".urlencode($newPassErr)."&confirmPassErr=".urlencode($confirmPassErr);
        header($url);
        exit();
    }

    updatePassword($user["user_id"], password_hash($newPass, PASSWORD_DEFAULT));

    header("Location: /campusconnect/views/account/profile.php?generalMessage=" . urlencode("Your password is changed."));
    exit();
}

?>
