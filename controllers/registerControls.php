<?php
require_once "authCheck.php";
require_once "../models/usersModel.php";

startSession();

if (isLoggedIn())
{
    $user = getLoggedUser();

    if ($user != null && $user["status"] == "active")
    {
        header("Location: /campusconnect".dashboardForRole($user["role"]));
        exit();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $name=trim($_POST["name"]);
    $email=trim($_POST["email"]);
    $password=$_POST["password"];
    $confirmPass=$_POST["confirmPass"];

    $hasErr=false;
    $nameErr="";
    $emailErr="";
    $passwordErr="";
    $confirmPassErr="";
    $generalErr="";

    if (empty($name)) {
        $nameErr="Name cannot be empty.";
        $hasErr=true;
    }


    elseif(!preg_match('/^[a-zA-Z\' -]+$/', $name))
    {
        $nameErr="Name cannot have numbers or special char";
        $hasErr=true;
    }

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
    else if (emailExists($email) == true)
    {
        $emailErr="This email is already registered.";
        $hasErr=true;
    }

    if (empty($password))
    {
        $passwordErr="Password cannot be empty.";
        $hasErr=true;
    }
    else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password))
    {
        $passwordErr="Must be at least 8 characters long and include an uppercase letter, lowercase letter, number, and special character.";
        $hasErr=true;
    }

    if ($password != $confirmPass)
    {
        $confirmPassErr="Confirm passwords and passwords didn't matched.";
        $hasErr=true;
    }

    if ($hasErr) {
        $url="Location: /campusconnect/views/register.php?name=".urlencode($name)."&email=".urlencode($email)."&nameErr=".urlencode($nameErr)."&emailErr=".urlencode($emailErr)."&passwordErr=".urlencode($passwordErr)."&confirmPassErr=".urlencode($confirmPassErr);
        header($url);
        exit();
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $newId = createStudent($name, $email, $passwordHash);

    if ($newId <= 0){
        $generalErr= "The account could not be created. Please try again.";
        header("Location: /campusconnect/views/register.php?generalErr=".urlencode($generalErr));
        exit();
    }

    $generalMessage="Your account is created. You can log in now.";

    header("Location: /campusconnect/views/login.php?generalMessage=".urlencode($generalMessage));
    exit();
}


?>