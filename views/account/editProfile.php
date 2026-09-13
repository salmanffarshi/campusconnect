<?php
require_once "../../controllers/authCheck.php";
$user = checkLogin();
$pageTitle = "Edit Profile";
require_once "../header.php";

$nameValue = $user["name"];
$emailValue = $user["email"];

if (isset($_GET["name"]))
{
    $nameValue = $_GET["name"];
}

if (isset($_GET["email"]))
{
    $emailValue = $_GET["email"];
}
?>

<div class="card card-narrow">
    <h1>Edit Profile</h1>

    <form action="/campusconnect/controllers/profileControls.php" method="post">

        <label for="name">Full Name</label>
        <input type="text" name="name" id="name" value="<?php echo $nameValue; ?>">
        <span class="error">
            <?php
            if (isset($_GET["nameErr"]))
            {
                echo $_GET["nameErr"];
            }
            ?>
        </span>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo $emailValue; ?>">
        <span class="error">
            <?php
            if (isset($_GET["emailErr"]))
            {
                echo $_GET["emailErr"];
            }
            ?>
        </span>

        <input type="submit" class="btn" value="Save Changes">
        <a class="btn btn-link" href="/campusconnect/views/account/profile.php">Cancel</a>
    </form>
</div>

<?php require_once "../footer.php"; ?>
