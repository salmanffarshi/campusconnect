<?php
require_once "authCheck.php";
require_once "../models/clubsModel.php";
require_once "../models/eventsModel.php";
require_once "../models/registrationsModel.php";
require_once "../models/attendanceModel.php";

$user = checkRoleAjax("organizer");

if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    sendJson(false, "Invalid request.");
}

$registrationId = (int) $_POST["registrationId"];
$status = $_POST["status"];

if ($status != "PRESENT" && $status != "ABSENT")
{
    sendJson(false, "Attendance must be PRESENT or ABSENT.");
}

$registration = findRegistrationById($registrationId);

if ($registration == null || $registration["status"] != "REGISTERED")
{
    sendJson(false, "That registration was not found.");
}

$event = findEventById($registration["event_id"]);
$club = findClubByOrganizer($user["user_id"]);

if ($club == null || $event["club_id"] != $club["club_id"])
{
    sendJson(false, "You can only mark attendance for your own events.");
}

if ($event["status"] != "APPROVED")
{
    sendJson(false, "Attendance can only be marked for approved events.");
}

saveAttendance($registrationId, $status);

sendJson(true, "Attendance saved.");

?>
