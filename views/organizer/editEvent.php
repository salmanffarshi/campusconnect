<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/clubsModel.php";
require_once "../../models/categoriesModel.php";
require_once "../../models/eventsModel.php";
$user = checkRole("organizer");
$club = findClubByOrganizer($user["user_id"]);
$categories = getAllCategories();

$eventId = 0;

if (isset($_GET["eventId"]))
{
    $eventId = (int) $_GET["eventId"];
}

$event = findEventById($eventId);

if ($club == null || $event == null || $event["club_id"] != $club["club_id"])
{
    header("Location: /campusconnect/views/organizer/myEvents.php?generalErr=" . urlencode("You can only edit events of your own club."));
    exit();
}

if ($event["status"] != "PENDING" && $event["status"] != "REJECTED")
{
    header("Location: /campusconnect/views/organizer/myEvents.php?generalErr=" . urlencode("Only pending or rejected events can be edited."));
    exit();
}

$values = array(
    "title" => $event["title"],
    "description" => $event["description"],
    "categoryId" => $event["category_id"],
    "eventDate" => $event["event_date"],
    "startTime" => substr($event["start_time"], 0, 5),
    "endTime" => substr($event["end_time"], 0, 5),
    "venue" => $event["venue"],
    "capacity" => $event["capacity"]
);

foreach ($values as $field => $value)
{
    if (isset($_GET[$field]))
    {
        $values[$field] = $_GET[$field];
    }
}

$pageTitle = "Edit Event";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card card-narrow" style="max-width: 560px;">
    <h1>Edit Event</h1>

    <?php if ($event["status"] == "REJECTED"): ?>
        <div class="flash flash-info">This event was rejected. Saving your changes sends it to the administrator again.</div>
    <?php else: ?>
        <p class="muted">This event is still waiting for approval.</p>
    <?php endif; ?>

    <?php require_once "eventForm.php"; ?>
</div>

<?php require_once "../footer.php"; ?>
