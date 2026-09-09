<?php

require_once "../../controllers/authCheck.php";
$user = checkRole("student");
$pageTitle = "Student Dashboard";
require_once "../header.php";

?>

<div class="card">
    <h1>Welcome, <?php echo $user["name"]; ?></h1>
    <p class="muted">Student dashboard</p>
</div>


<?php require_once "../footer.php"; ?>
