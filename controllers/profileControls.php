<?php

require_once "authCheck.php";
require_once "../models/usersModel.php";

$user = checkLogin();

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);

    $hasErr = false;
    $nameErr = "";
    $emailErr = "";

    if (empty($name))
    {
        $nameErr = "Name cannot be empty.";
        $hasErr = true;
    }
    else if (!preg_match('/^[a-zA-Z\' -]+$/', $name))
    {
        $nameErr = "Name cannot have numbers or special char";
        $hasErr = true;
    }

    if (empty($email))
    {
        $emailErr = "Email cannot be empty.";
        $hasErr = true;
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $emailErr = "Please write a valid email address.";
        $hasErr = true;
    }
    else
    {
        $owner = findUserByEmail($email);

        if ($owner != null && $owner["user_id"] != $user["user_id"])
        {
            $emailErr = "That email is already used by another account.";
            $hasErr = true;
        }
    }

    if ($hasErr)
    {
        $url = "Location: /campusconnect/views/account/editProfile.php?name=".urlencode($name)."&email=".urlencode($email)."&nameErr=".urlencode($nameErr)."&emailErr=".urlencode($emailErr);
        header($url);
        exit();
    }

    updateProfile($user["user_id"], $name, $email);

    
    $_SESSION["name"] = $name;

    header("Location: /campusconnect/views/account/profile.php?generalMessage=" . urlencode("Your profile is updated."));
    exit();
}

?>
