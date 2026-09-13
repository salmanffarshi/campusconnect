<?php
require_once "../../controllers/authCheck.php";
$user = checkRole("admin");
$pageTitle = "Create Organizer";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card card-narrow" style="max-width: 560px;">
    <h1>Create Organizer</h1>
    <p class="muted">Organizers cannot register themselves. Create the account and the club it manages together.</p>

    <form action="/campusconnect/controllers/createOrganizerControls.php" method="post">

        <h2 style="margin-top: 16px;">Organizer Account</h2>

        <label for="name">Full Name</label>
        <input type="text" name="name" id="name"
               value="<?php if (isset($_GET["name"])) { echo $_GET["name"]; } ?>">
        <span class="error">
            <?php
            if (isset($_GET["nameErr"]))
            {
                echo $_GET["nameErr"];
            }
            ?>
        </span>

        <label for="email">Email</label>
        <input type="email" name="email" id="email"
               value="<?php if (isset($_GET["email"])) { echo $_GET["email"]; } ?>">
        <span class="error">
            <?php
            if (isset($_GET["emailErr"]))
            {
                echo $_GET["emailErr"];
            }
            ?>
        </span>

        <label for="password">Password</label>
        <input type="password" name="password" id="password">
        <span class="error">
            <?php
            if (isset($_GET["passwordErr"]))
            {
                echo $_GET["passwordErr"];
            }
            ?>
        </span>

        <label for="confirmPass">Write the password again</label>
        <input type="password" name="confirmPass" id="confirmPass">
        <span class="error">
            <?php
            if (isset($_GET["confirmPassErr"]))
            {
                echo $_GET["confirmPassErr"];
            }
            ?>
        </span>

        <h2 style="margin-top: 24px;">Club</h2>

        <label for="clubName">Club Name</label>
        <input type="text" name="clubName" id="clubName"
               value="<?php if (isset($_GET["clubName"])) { echo $_GET["clubName"]; } ?>">
        <span class="error">
            <?php
            if (isset($_GET["clubNameErr"]))
            {
                echo $_GET["clubNameErr"];
            }
            ?>
        </span>

        <label for="clubDescription">Club Description (optional)</label>
        <textarea name="clubDescription" id="clubDescription" rows="3"><?php if (isset($_GET["clubDescription"])) { echo $_GET["clubDescription"]; } ?></textarea>
        <span class="error">
            <?php
            if (isset($_GET["clubDescriptionErr"]))
            {
                echo $_GET["clubDescriptionErr"];
            }
            ?>
        </span>

        <input type="submit" class="btn" value="Create Organizer">
        <a class="btn btn-link" href="/campusconnect/views/admin/users.php">Cancel</a>
    </form>
</div>

<?php require_once "../footer.php"; ?>
