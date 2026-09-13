<?php

require_once "authCheck.php";
require_once "../models/eventsModel.php";

$user = checkRoleAjax("student");

$search = "";
$categoryId = 0;

if (isset($_GET["search"]))
{
    $search = trim($_GET["search"]);
}

if (isset($_GET["categoryId"]))
{
    $categoryId = (int) $_GET["categoryId"];
}

$events = searchApprovedEvents($search, $categoryId);

sendJson(true, count($events) . " events found.", $events);

?>
