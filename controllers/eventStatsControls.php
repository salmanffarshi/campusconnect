<?php

require_once "authCheck.php";
require_once "../models/clubsModel.php";
require_once "../models/eventsModel.php";
require_once "../models/statisticsModel.php";

$user = checkRoleAjax("organizer");

$eventId = 0;

if (isset($_GET["eventId"]))
{
    $eventId = (int) $_GET["eventId"];
}

$event = findEventById($eventId);
$club = findClubByOrganizer($user["user_id"]);

if ($club == null || $event == null || $event["club_id"] != $club["club_id"])
{
    sendJson(false, "You can only see statistics of your own events.");
}

sendJson(true, "Statistics loaded.", getEventStatistics($eventId));

?>
