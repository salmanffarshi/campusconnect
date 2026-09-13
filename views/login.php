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

$pageTitle = "Login";
require_once "header.php";

?>

<div class="card card-narrow">
    <h1>Login</h1>

    <form action="/campusconnect/controllers/loginControls.php" method="post">

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
            if (isset($_GET["passErr"]))
            {
                echo $_GET["passErr"];
            }
            ?>
        </span>

        <input type="submit" class="btn" value="Login">
    </form>
    <span class="error">
        <?php
        if (isset($_GET["generalErr"]))
        {
            echo $_GET["generalErr"];
        }
        ?>

    </span>

    <span class="success">
        <?php
        if (isset($_GET["generalMessage"]))
        {
            echo $_GET["generalMessage"];
        }
        ?>

    </span>

    <p class="muted">
        <a href="/campusconnect/views/forgotPassword.php">Forgot password?</a>
    </p>
    <p class="muted">
        New student? <a href="/campusconnect/views/register.php">Create an account</a>
    </p>
</div>

<?php 

require_once "footer.php"; 

?>
