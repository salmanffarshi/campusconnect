<?php
require_once "../../controllers/authCheck.php";
$user = checkRole("admin");    
$pageTitle = "Users";
require_once "../header.php";
?>

<div class="card">
    <h1>Users</h1>
    <p class="muted">This page is not built yet. I will assign to someone</p>
</div>

<?php require_once "../footer.php"; ?>