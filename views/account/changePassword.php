<?php
require_once "../../controllers/authCheck.php";
$user = checkLogin();
$pageTitle = "Change Password";
require_once "../header.php";
?>

<div class="card card-narrow">
    <h1>Change Password</h1>
    <p class="muted">You must write your current password to set a new one.</p>

    <form action="/campusconnect/controllers/changePasswordControls.php" method="post">

        <label for="currentPass">Current Password</label>
        <input type="password" name="currentPass" id="currentPass">
        <span class="error">
            <?php
            if (isset($_GET["currentPassErr"]))
            {
                echo $_GET["currentPassErr"];
            }
            ?>
        </span>

        <label for="newPass">New Password</label>
        <input type="password" name="newPass" id="newPass">
        <span class="error">
            <?php
            if (isset($_GET["newPassErr"]))
            {
                echo $_GET["newPassErr"];
            }
            ?>
        </span>

        <label for="confirmPass">Write the new password again</label>
        <input type="password" name="confirmPass" id="confirmPass">
        <span class="error">
            <?php
            if (isset($_GET["confirmPassErr"]))
            {
                echo $_GET["confirmPassErr"];
            }
            ?>
        </span>

        <input type="submit" class="btn" value="Change Password">
        <a class="btn btn-link" href="/campusconnect/views/account/profile.php">Cancel</a>
    </form>
</div>

<?php require_once "../footer.php"; ?>
