<?php

require_once "../../controllers/authCheck.php";
$user = checkRole("admin");
$pageTitle = "Admin Dashboard";
require_once "../header.php";

?>

<div class="card">
    <h1>Welcome, <?php $user["name"]; ?></h1>
    <p class="muted">Administrator dashboard</p>
</div>


<?php require_once "../footer.php"; ?>
