<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/eventsModel.php";
$user = checkRole("admin");

$eventId = 0;

if (isset($_GET["eventId"]))
{
    $eventId = (int) $_GET["eventId"];
}

$event = findEventDetails($eventId);

if ($event == null)
{
    header("Location: /campusconnect/views/admin/eventApproval.php?generalErr=" . urlencode("Event not found."));
    exit();
}

$pageTitle = "Review Event";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <a href="/campusconnect/views/admin/eventApproval.php">&larr; Back to event approval</a>
    <h1 style="margin-top: 12px;"><?php echo $event["title"]; ?></h1>
    <p><span class="badge badge-<?php echo strtolower($event["status"]); ?>"><?php echo $event["status"]; ?></span></p>

    <table class="details-table">
        <tr>
            <th>Club</th>
            <td><?php echo $event["club_name"]; ?></td>
        </tr>
        <tr>
            <th>Organizer</th>
            <td><?php echo $event["organizer_name"] . " (" . $event["organizer_email"] . ")"; ?></td>
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
            <th>Capacity</th>
            <td><?php echo $event["registered_count"] . " registered of " . $event["capacity"]; ?></td>
        </tr>
        <tr>
            <th>Submitted</th>
            <td><?php echo date("d M Y, H:i", strtotime($event["created_at"])); ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?php echo $event["description"]; ?></td>
        </tr>
    </table>
</div>

<div class="card">
    <h2>Decision</h2>

    <?php if ($event["status"] == "PENDING" || $event["status"] == "APPROVED"): ?>

        <?php if ($event["status"] == "PENDING"): ?>
            <form style="display: inline;" action="/campusconnect/controllers/eventStatusControls.php" method="post">
                <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                <input type="hidden" name="status" value="APPROVED">
                <input type="submit" class="btn btn-green" value="Approve">
            </form>

            <form style="display: inline;" action="/campusconnect/controllers/eventStatusControls.php" method="post">
                <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
                <input type="hidden" name="status" value="REJECTED">
                <input type="submit" class="btn btn-danger" value="Reject">
            </form>
        <?php endif; ?>

        <form style="display: inline;" action="/campusconnect/controllers/eventStatusControls.php" method="post"
              onsubmit="return confirm('Cancel this event?');">
            <input type="hidden" name="eventId" value="<?php echo $eventId; ?>">
            <input type="hidden" name="status" value="CANCELLED">
            <input type="submit" class="btn btn-danger" value="Cancel Event">
        </form>

    <?php else: ?>
        <p class="muted">No decision is needed for a <?php echo strtolower($event["status"]); ?> event.</p>
    <?php endif; ?>
</div>

<?php require_once "../footer.php"; ?>
