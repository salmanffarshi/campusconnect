<?php

if (isset($pageTitle) == false)
{
    $pageTitle = "CampusConnect";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $pageTitle; ?> | CampusConnect</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="navbar">
    <a class="brand" href="/campusconnect/index.php">CampusConnect</a>

    <div class="nav-links">
        <a href="/campusconnect/views/login.php">Login</a>
        <a href="/campusconnect/views/register.php">Register</a>  
    </div>
</div>

<div class="container">

