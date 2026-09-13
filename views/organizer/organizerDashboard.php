<?php

require_once "../../controllers/authCheck.php";
$user = checkRole("organizer");
$pageTitle = "Organizer Dashboard";
require_once "../header.php";

?>

<div class="card">
    <h1>Welcome, <?php echo $user["name"]; ?></h1>
    <p class="muted">Club organizer dashboard</p>
</div>


<?php require_once "../footer.php"; ?>
