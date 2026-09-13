<?php

require_once "authCheck.php";
require_once "../models/eventsModel.php";

$admin = checkRole("admin");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $eventId = (int) $_POST["eventId"];
    $newStatus = $_POST["status"];

    $event = findEventById($eventId);

    if ($event == null)
    {
        header("Location: /campusconnect/views/admin/eventApproval.php?generalErr=" . urlencode("Event not found."));
        exit();
    }

    $allowed = false;

    if ($event["status"] == "PENDING")
    {
        if ($newStatus == "APPROVED" || $newStatus == "REJECTED" || $newStatus == "CANCELLED")
        {
            $allowed = true;
        }
    }
    else if ($event["status"] == "APPROVED")
    {
        if ($newStatus == "CANCELLED")
        {
            $allowed = true;
        }
    }

    if ($allowed == false)
    {
        $message = "An event that is " . strtolower($event["status"]) . " cannot be changed to " . strtolower($newStatus) . ".";
        header("Location: /campusconnect/views/admin/eventApproval.php?generalErr=" . urlencode($message));
        exit();
    }

    setEventStatus($eventId, $newStatus);

    $message = "\"" . $event["title"] . "\" is now " . strtolower($newStatus) . ".";
    header("Location: /campusconnect/views/admin/eventApproval.php?generalMessage=" . urlencode($message));
    exit();
}

?>
