<?php
require_once "dbConnect.php";

// Every organizer manages exactly one club (clubs.organizer_id is UNIQUE).
function findClubByOrganizer($organizerId)
{
    $sql = "SELECT * FROM clubs WHERE organizer_id = ?";
    $result = executeQuery($sql, "i", $organizerId);
    return mysqli_fetch_assoc($result);
}

function clubNameExists($clubName)
{
    $sql = "SELECT club_id FROM clubs WHERE club_name = ?";
    $result = executeQuery($sql, "s", $clubName);

    if (mysqli_fetch_assoc($result) == null)
    {
        return false;
    }

    return true;
}

function createClub($clubName, $description, $organizerId)
{
    $sql = "INSERT INTO clubs (club_name, description, organizer_id) VALUES (?, ?, ?)";
    return executeInsert($sql, "ssi", $clubName, $description, $organizerId);
}

?>
