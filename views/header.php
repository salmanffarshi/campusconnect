<?php
require_once __DIR__ . "/../controllers/authCheck.php";

$menuUser = getLoggedUser();

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
    <link rel="stylesheet" href="/campusconnect/views/css/style.css">
</head>

<body>

<div class="navbar">
    <a class="brand" href="/campusconnect/index.php">CampusConnect</a>

    <div class="nav-links">
        <?php if ($menuUser == null): ?>             

            <a href="/campusconnect/views/login.php">Login</a>
            <a href="/campusconnect/views/register.php">Register</a>

        <?php elseif ($menuUser["role"] == "student"): ?>             

            <a href="/campusconnect/views/student/studentDashboard.php">Dashboard</a>
            <a href="/campusconnect/views/student/events.php">Events</a>
            <a href="/campusconnect/views/student/myRegistrations.php">My Registrations</a>
            <a href="/campusconnect/views/account/profile.php">Profile</a>
            <a href="/campusconnect/views/logout.php">Logout</a>

        <?php elseif ($menuUser["role"] == "organizer"): ?>             

            <a href="/campusconnect/views/organizer/statistics.php">Statistics</a>
            <a href="/campusconnect/views/organizer/myEvents.php">My Events</a>
            <a href="/campusconnect/views/organizer/createEvent.php">Create Event</a>
            <a href="/campusconnect/views/account/profile.php">Profile</a>
            <a href="/campusconnect/views/logout.php">Logout</a>

        <?php elseif ($menuUser["role"] == "admin"): ?>

            <a href="/campusconnect/views/admin/statistics.php">Statistics</a>
            <a href="/campusconnect/views/admin/users.php">Users</a>
            <a href="/campusconnect/views/admin/eventApproval.php">Event Approval</a>
            <a href="/campusconnect/views/account/profile.php">Profile</a>
            <a href="/campusconnect/views/logout.php">Logout</a>

        <?php endif; ?>        
    </div>
</div>

<div class="container">

