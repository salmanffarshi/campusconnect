<?php
require_once "authCheck.php";
require_once "../models/clubsModel.php";
require_once "../models/eventsModel.php";

$user = checkRole("organizer");
$club = findClubByOrganizer($user["user_id"]);

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $eventId = (int) $_POST["eventId"];
    $event = findEventById($eventId);

    if ($club == null || $event == null || $event["club_id"] != $club["club_id"])
    {
        header("Location: /campusconnect/views/organizer/myEvents.php?generalErr=" . urlencode("You can only cancel events of your own club."));
        exit();
    }

    if ($event["status"] != "PENDING" && $event["status"] != "APPROVED")
    {
        header("Location: /campusconnect/views/organizer/myEvents.php?generalErr=" . urlencode("This event cannot be cancelled."));
        exit();
    }

    setEventStatus($eventId, "CANCELLED");

    header("Location: /campusconnect/views/organizer/myEvents.php?generalMessage=" . urlencode("\"" . $event["title"] . "\" has been cancelled."));
    exit();
}

?>
