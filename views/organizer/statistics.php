<?php
require_once "../../controllers/authCheck.php";
$user = checkRole("organizer");    
$pageTitle = "Event Statistics";
require_once "../header.php";
?>

<div class="card">
    <h1>Welcome, <?php echo $user["name"]; ?></h1>
    <p class="muted">Event statistics are not built yet.</p>
</div>

<div class="card">
    <h2>What you can do now</h2>
    <ul>
        <li><a href="/campusconnect/views/organizer/myEvents.php">My events</a></li>
        <li><a href="/campusconnect/views/organizer/createEvent.php">Create event</a></li>
        <li><a href="/campusconnect/views/account/profile.php">My profile</a></li>
    </ul>
</div>

<?php require_once "../footer.php"; ?>
