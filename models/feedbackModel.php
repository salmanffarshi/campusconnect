<?php
require_once "dbConnect.php";

function findFeedbackByRegistration($registrationId)
{
    $sql = "SELECT * FROM feedback WHERE registration_id = ?";
    $result = executeQuery($sql, "i", $registrationId);
    return mysqli_fetch_assoc($result);
}

// One feedback per registration (registration_id is UNIQUE).
function createFeedback($registrationId, $rating, $comment)
{
    $sql = "INSERT INTO feedback (registration_id, rating, comment) VALUES (?, ?, ?)";
    return executeInsert($sql, "iis", $registrationId, $rating, $comment);
}

?>
