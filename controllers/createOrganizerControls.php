<?php

require_once "authCheck.php";
require_once "../models/usersModel.php";
require_once "../models/clubsModel.php";

$admin = checkRole("admin");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];
    $confirmPass = $_POST["confirmPass"];
    $clubName = trim($_POST["clubName"]);
    $clubDescription = trim($_POST["clubDescription"]);

    $hasErr = false;
    $nameErr = "";
    $emailErr = "";
    $passwordErr = "";
    $confirmPassErr = "";
    $clubNameErr = "";
    $clubDescriptionErr = "";

    if (empty($name))
    {
        $nameErr = "Name cannot be empty.";
        $hasErr = true;
    }
    else if (!preg_match('/^[a-zA-Z\' -]+$/', $name))
    {
        $nameErr = "Name cannot have numbers or special char";
        $hasErr = true;
    }

    if (empty($email))
    {
        $emailErr = "Email cannot be empty.";
        $hasErr = true;
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $emailErr = "Please write a valid email address.";
        $hasErr = true;
    }
    else if (emailExists($email) == true)
    {
        $emailErr = "This email is already registered.";
        $hasErr = true;
    }

    if (empty($password))
    {
        $passwordErr = "Password cannot be empty.";
        $hasErr = true;
    }
    else if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password))
    {
        $passwordErr = "Must be at least 8 characters long and include an uppercase letter, lowercase letter, number, and special character.";
        $hasErr = true;
    }

    if ($password != $confirmPass)
    {
        $confirmPassErr = "Confirm password and password didn't match.";
        $hasErr = true;
    }

    if (empty($clubName))
    {
        $clubNameErr = "Club name cannot be empty.";
        $hasErr = true;
    }
    else if (strlen($clubName) > 150)
    {
        $clubNameErr = "Club name cannot be longer than 150 letters.";
        $hasErr = true;
    }
    else if (clubNameExists($clubName) == true)
    {
        $clubNameErr = "A club with this name already exists.";
        $hasErr = true;
    }

    if (strlen($clubDescription) > 1000)
    {
        $clubDescriptionErr = "Description cannot be longer than 1000 letters.";
        $hasErr = true;
    }

    // Send back what was typed and the messages, never the passwords.
    if ($hasErr)
    {
        $url = "Location: /campusconnect/views/admin/createOrganizer.php?name=" . urlencode($name)
             . "&email=" . urlencode($email)
             . "&clubName=" . urlencode($clubName)
             . "&clubDescription=" . urlencode($clubDescription)
             . "&nameErr=" . urlencode($nameErr)
             . "&emailErr=" . urlencode($emailErr)
             . "&passwordErr=" . urlencode($passwordErr)
             . "&confirmPassErr=" . urlencode($confirmPassErr)
             . "&clubNameErr=" . urlencode($clubNameErr)
             . "&clubDescriptionErr=" . urlencode($clubDescriptionErr);
        header($url);
        exit();
    }

    $organizerId = createOrganizer($name, $email, password_hash($password, PASSWORD_DEFAULT));

    if ($organizerId <= 0)
    {
        header("Location: /campusconnect/views/admin/createOrganizer.php?generalErr=" . urlencode("The organizer account could not be created."));
        exit();
    }

    $clubId = createClub($clubName, $clubDescription, $organizerId);

    if ($clubId <= 0)
    {
        // The account exists but has no club, so we switch it off rather
        // than leave a half-made organizer who can log in but do nothing.
        setUserStatus($organizerId, "inactive");

        header("Location: /campusconnect/views/admin/users.php?generalErr=" . urlencode("The account was created but the club could not be saved, so the account was made inactive."));
        exit();
    }

    header("Location: /campusconnect/views/admin/users.php?generalMessage=" . urlencode($name . " is now the organizer of " . $clubName . "."));
    exit();
}

?>
