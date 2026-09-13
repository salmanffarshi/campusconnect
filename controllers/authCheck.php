<?php

require_once __DIR__ . "/../models/usersModel.php";

function startSession(){
    if (session_status() == PHP_SESSION_NONE)
    {
        session_start();
    }
}

function isLoggedIn()
{
    startSession();

    if (isset($_SESSION["userId"]) && isset($_SESSION["role"]))
    {
        return true;
    }

    return false;
}

function getLoggedUser()
{
    startSession();

    if (isLoggedIn() == false)
    {
        return null;
    }

    return findUserById($_SESSION["userId"]);
}

function logoutUser()
{
    startSession();
    session_unset();
    session_destroy();
}


function checkLogin()
{
    startSession();

    if (isLoggedIn() == false)
    {
        $generalErr="Please log in to continue.";
        header("Location: /campusconnect/views/login.php?generalErr=" . urlencode($generalErr));
        exit();
    }

    $user = findUserById($_SESSION["userId"]);   
    if ($user == null || $user["status"] != "active")
    {
        logoutUser();
        $generalErr="Your account is no longer active.";
        header("Location: /campusconnect/views/login.php?generalErr=" . urlencode($generalErr));
        exit();
    }

    return $user;
}


function checkRole($role)
{
    $user = checkLogin();

    if ($user["role"] != $role)
    {
        
        header("Location: /campusconnect" . dashboardForRole($user["role"]));
        exit();
    }

    return $user;
}


function dashboardForRole($role)
{
    if ($role == "admin")
    {
        return "/views/admin/statistics.php";
    }
    else if ($role == "organizer")
    {
        return "/views/organizer/statistics.php";
    }
    else if ($role == "student")
    {
        return "/views/student/studentDashboard.php";
    }

    return "/views/login.php";
}




?>