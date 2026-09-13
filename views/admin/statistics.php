<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/statisticsModel.php";
$user = checkRole("admin");

$numbers = getSystemStatistics();

$pageTitle = "Admin Dashboard";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <h1>Welcome, <?php echo $user["name"]; ?></h1>
    <p class="muted">Administrator dashboard</p>
</div>

<h2>System Statistics</h2>

<div class="tiles">
    <div class="tile">
        <div class="value"><?php echo $numbers["totalUsers"]; ?></div>
        <div class="label">Total users</div>
    </div>
    <div class="tile">
        <div class="value"><?php echo $numbers["totalStudents"]; ?></div>
        <div class="label">Total students</div>
    </div>
    <div class="tile">
        <div class="value"><?php echo $numbers["totalOrganizers"]; ?></div>
        <div class="label">Total organizers</div>
    </div>
    <div class="tile">
        <div class="value"><?php echo $numbers["totalEvents"]; ?></div>
        <div class="label">Total events</div>
    </div>
    <div class="tile">
        <div class="value"><?php echo $numbers["totalRegistrations"]; ?></div>
        <div class="label">Total registrations</div>
    </div>
</div>

<div class="card" style="margin-top: 20px;">
    <h2>What you can do</h2>
    <ul>
        <li><a href="/campusconnect/views/admin/eventApproval.php">Event approval</a> &mdash; approve, reject or cancel events</li>
        <li><a href="/campusconnect/views/admin/users.php">Users</a> &mdash; search, activate or deactivate accounts</li>
        <li><a href="/campusconnect/views/admin/createOrganizer.php">Create organizer</a> &mdash; new organizer account and club</li>
    </ul>
</div>

<?php require_once "../footer.php"; ?>
