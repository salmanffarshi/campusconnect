<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/eventsModel.php";
require_once "../../models/registrationsModel.php";
$user = checkRole("student");

$eventId = 0;

if (isset($_GET["eventId"]))
{
    $eventId = (int) $_GET["eventId"];
}

$event = findEventDetails($eventId);

// Students can only see APPROVED events.
if ($event == null || $event["status"] != "APPROVED")
{
    header("Location: /campusconnect/views/student/events.php?generalErr=" . urlencode("That event is not available."));
    exit();
}

$registration = findRegistration($eventId, $user["user_id"]);
$seatsLeft = $event["capacity"] - $event["registered_count"];
$isPast = $event["event_date"] < date("Y-m-d");

$isRegistered = false;

if ($registration != null && $registration["status"] == "REGISTERED")
{
    $isRegistered = true;
}

$pageTitle = $event["title"];
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <a href="/campusconnect/views/student/events.php">&larr; Back to events</a>
    <h1 style="margin-top: 12px;"><?php echo $event["title"]; ?></h1>

    <table class="details-table">
        <tr>
            <th>Club</th>
            <td><?php echo $event["club_name"]; ?></td>
        </tr>
        <tr>
            <th>Category</th>
            <td><?php echo $event["category_name"]; ?></td>
        </tr>
        <tr>
            <th>Date</th>
            <td><?php echo date("l, d M Y", strtotime($event["event_date"])); ?></td>
        </tr>
        <tr>
            <th>Time</th>
            <td><?php echo substr($event["start_time"], 0, 5) . " - " . substr($event["end_time"], 0, 5); ?></td>
        </tr>
        <tr>
            <th>Venue</th>
            <td><?php echo $event["venue"]; ?></td>
        </tr>
        <tr>
            <th>Seats</th>
            <td><?php echo $event["registered_count"] . " registered of " . $event["capacity"]; ?></td>
        </tr>
        <tr>
            <th>Organizer</th>
            <td><?php echo $event["organizer_name"]; ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?php echo $event["description"]; ?></td>
        </tr>
    </table>
</div>

<div class="card">
    <h2>Registration</h2>
    <div id="message"></div>

    <?php if ($isRegistered): ?>

        <p><span class="badge badge-approved">You are registered for this event</span></p>

        <?php if ($isPast == false): ?>
            <button class="btn btn-danger" onclick="cancelRegistration(<?php echo $eventId; ?>)">Cancel My Registration</button>
        <?php endif; ?>

    <?php elseif ($isPast): ?>

        <p class="muted">This event has already happened.</p>

    <?php elseif ($seatsLeft <= 0): ?>

        <p><span class="badge badge-rejected">This event is full</span></p>

    <?php else: ?>

        <p class="muted"><?php echo $seatsLeft; ?> seats left.</p>
        <button class="btn btn-green" onclick="registerForEvent(<?php echo $eventId; ?>)">Register for this Event</button>

    <?php endif; ?>
</div>

<script src="/campusconnect/views/student/js/registration.js"></script>

<?php require_once "../footer.php"; ?>
