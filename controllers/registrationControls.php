<?php

require_once "authCheck.php";
require_once "../models/eventsModel.php";
require_once "../models/registrationsModel.php";

$user = checkRoleAjax("student");

if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    sendJson(false, "Invalid request.");
}

$action = "";
$eventId = 0;

if (isset($_POST["action"]))
{
    $action = $_POST["action"];
}

if (isset($_POST["eventId"]))
{
    $eventId = (int) $_POST["eventId"];
}

$event = findEventById($eventId);

if ($event == null)
{
    sendJson(false, "Event not found.");
}

$today = date("Y-m-d");
$registration = findRegistration($eventId, $user["user_id"]);

if ($action == "register")
{
    if ($event["status"] != "APPROVED")
    {
        sendJson(false, "You can only register for approved events.");
    }

    if ($event["event_date"] < $today)
    {
        sendJson(false, "This event has already happened.");
    }

    if ($registration != null && $registration["status"] == "REGISTERED")
    {
        sendJson(false, "You are already registered for this event.");
    }

    if (countActiveRegistrations($eventId) >= $event["capacity"])
    {
        sendJson(false, "Sorry, this event is full.");
    }

    $clash = findTimeClash($user["user_id"], $eventId, $event["event_date"], $event["start_time"], $event["end_time"]);

    if ($clash != null)
    {
        sendJson(false, "You cannot register: this event overlaps with \"" . $clash["title"] . "\" ("
            . substr($clash["start_time"], 0, 5) . " - " . substr($clash["end_time"], 0, 5)
            . "), which you are already registered for.");
    }

    if ($registration != null)
    {
        reactivateRegistration($registration["registration_id"]);
    }
    else
    {
        $newId = createRegistration($eventId, $user["user_id"]);

        if ($newId <= 0)
        {
            sendJson(false, "The registration could not be saved. Please try again.");
        }
    }

    sendJson(true, "You are registered for \"" . $event["title"] . "\".");
}
else if ($action == "cancel")
{
    if ($registration == null || $registration["status"] != "REGISTERED")
    {
        sendJson(false, "You are not registered for this event.");
    }

    if ($event["event_date"] < $today)
    {
        sendJson(false, "You cannot cancel after the event has happened.");
    }

    cancelRegistration($registration["registration_id"]);

    sendJson(true, "Your registration for \"" . $event["title"] . "\" is cancelled.");
}

sendJson(false, "Unknown action.");

?>
