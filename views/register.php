<?php

require_once "../controllers/authCheck.php";

startSession();

if (isLoggedIn())
{
    $user = getLoggedUser();

    if ($user != null && $user["status"] == "active")
    {
        header("Location: /campusconnect" . dashboardForRole($user["role"]));
        exit();
    }
}

$pageTitle = "Registration";
require_once "header.php";

?>

<div class="card card-narrow">
    <h1>Create an account</h1>

    <form action="/campusconnect/controllers/registerControls.php" method="post">

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

        <input type="submit" class="btn" value="Register">
    </form>
    <span class="error">
        <?php
        if (isset($_GET["generalErr"]))
        {
            echo $_GET["generalErr"];
        }
        ?>

    </span>

    <p class="muted">
        Already have an account? <a href="/campusconnect/views/login.php">Login</a>
    </p>

    
</div>

<?php 

require_once "footer.php"; 

?>
