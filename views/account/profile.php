<?php
require_once "../../controllers/authCheck.php";
$user = checkLogin();
$pageTitle = "Profile";
require_once "../header.php";
require_once "../messages.php";

?>
<div class="card">
    <h1>My Profile</h1>

    <table class="details-table">
        <tr>
            <th>Name</th>
            <td><?php echo $user["name"]; ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo $user["email"]; ?></td>
        </tr>
        <tr>
            <th>Role</th>
            <td><?php echo $user["role"]; ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?php echo $user["status"]; ?></td>
        </tr>
        <tr>
            <th>Member since</th>
            <td><?php echo date("d M Y", strtotime($user["created_at"])); ?></td>
        </tr>
    </table>

    <a class="btn" href="/campusconnect/views/account/editProfile.php">Edit Profile</a>
    <a class="btn" href="/campusconnect/views/account/changePassword.php">Change Password</a>
</div>

<?php if ($user["role"] != "admin"): ?>
<div class="card">
    <h2>Delete Account</h2>
    <p class="muted">
        Your account will be made inactive and you will be logged out.
        Your old registrations stay in the system.
    </p>

    <form action="/campusconnect/controllers/deleteAccountControls.php" method="post"
          onsubmit="return confirm('Delete your account? You will not be able to log in again.');">

        <label for="password">Write your password to confirm</label>
        <input type="password" name="password" id="password">
        <span class="error">
            <?php
            if (isset($_GET["deleteErr"]))
            {
                echo $_GET["deleteErr"];
            }
            ?>
        </span>
        <br>
        <input type="submit" class="btn btn-danger" value="Delete My Account">
    </form>
</div>
<?php endif; ?>

<?php require_once "../footer.php"; ?>


