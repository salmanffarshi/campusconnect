<?php
require_once "authCheck.php";
require_once "../models/usersModel.php";

startSession();

if (isLoggedIn())
{
    $user = getLoggedUser();

    if ($user != null && $user["status"] == "active")
    {
        header("Location: /campusconnect" . dashboardForRole($user["role"]));
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $email =trim($_POST["email"]);
    $password =$_POST["password"];

    $hasErr=false;
    $emailErr="";
    $passErr="";
    $generalErr="";


    if (empty($email))
    {
        $emailErr="Email cannot be empty.";
        $hasErr=true;
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $emailErr="Please write a valid email address.";
        $hasErr=true;

    }

    if (empty($password))
    {
        $passErr="Password cannot be empty.";
        $hasErr=true;
    }

    if ($hasErr)
    {
        $url="Location: /campusconnect/views/login.php?email=".urlencode($email)."&emailErr=".urlencode($emailErr)."&passErr=".urlencode($passErr);
        header($url);
        exit();
    }

    $user = findUserByEmail($email);

    if ($user == null || !password_verify($password, $user["password"]))
    {
        $generalErr = "Invalid email or password.";
        
        header("Location: /campusconnect/views/login.php?generalErr=".urlencode($generalErr));
        exit();
    }

    if ($user["status"] != "active")
    {
        $generalErr= "This account is inactive. Please contact the administrator.";
        header("Location: /campusconnect/views/login.php?generalErr=".urlencode($generalErr));
        exit();
    }

    $_SESSION["userId"] = $user["user_id"];
    $_SESSION["role"] = $user["role"];
    $_SESSION["name"] = $user["name"];

    header("Location: /campusconnect" . dashboardForRole($user["role"]));
    exit();


}



?>
