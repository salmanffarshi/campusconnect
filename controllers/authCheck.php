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




?>