<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/eventsModel.php";
$user = checkRole("admin");

// Two lists: events waiting for a decision, and approved events that can
// still be cancelled.
$pendingEvents = getEventsForAdmin("PENDING");
$approvedEvents = getEventsForAdmin("APPROVED");

$pageTitle = "Event Approval";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <h1>Event Approval</h1>
    <p class="muted">New events from organizers are PENDING. Students only see APPROVED events.</p>
</div>

<div class="card">
    <h2>Waiting for a decision (<?php echo count($pendingEvents); ?>)</h2>

    <?php if (count($pendingEvents) == 0): ?>

        <p class="muted">No events are waiting.</p>

    <?php else: ?>

    <div class="table-wrap">
        <table>
            <tr>
                <th>Title</th>
                <th>Club</th>
                <th>Category</th>
                <th>Date &amp; Time</th>
                <th>Venue</th>
                <th>Capacity</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($pendingEvents as $event): ?>
            <tr>
                <td><a href="/campusconnect/views/admin/eventDetails.php?eventId=<?php echo $event["event_id"]; ?>"><?php echo $event["title"]; ?></a></td>
                <td><?php echo $event["club_name"]; ?></td>
                <td><?php echo $event["category_name"]; ?></td>
                <td>
                    <?php echo date("d M Y", strtotime($event["event_date"])); ?><br>
                    <span class="muted"><?php echo substr($event["start_time"], 0, 5) . " - " . substr($event["end_time"], 0, 5); ?></span>
                </td>
                <td><?php echo $event["venue"]; ?></td>
                <td><?php echo $event["capacity"]; ?></td>
                <td class="actions">
                    <form style="display: inline;" action="/campusconnect/controllers/eventStatusControls.php" method="post">
                        <input type="hidden" name="eventId" value="<?php echo $event["event_id"]; ?>">
                        <input type="hidden" name="status" value="APPROVED">
                        <input type="submit" class="btn btn-small btn-green" value="Approve">
                    </form>

                    <form style="display: inline;" action="/campusconnect/controllers/eventStatusControls.php" method="post">
                        <input type="hidden" name="eventId" value="<?php echo $event["event_id"]; ?>">
                        <input type="hidden" name="status" value="REJECTED">
                        <input type="submit" class="btn btn-small btn-danger" value="Reject">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php endif; ?>
</div>

<div class="card">
    <h2>Approved events (<?php echo count($approvedEvents); ?>)</h2>
    <p class="muted">An approved event can still be cancelled if it must be removed.</p>

    <?php if (count($approvedEvents) == 0): ?>

        <p class="muted">No approved events.</p>

    <?php else: ?>

    <div class="table-wrap">
        <table>
            <tr>
                <th>Title</th>
                <th>Club</th>
                <th>Date &amp; Time</th>
                <th>Venue</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($approvedEvents as $event): ?>
            <tr>
                <td><a href="/campusconnect/views/admin/eventDetails.php?eventId=<?php echo $event["event_id"]; ?>"><?php echo $event["title"]; ?></a></td>
                <td><?php echo $event["club_name"]; ?></td>
                <td>
                    <?php echo date("d M Y", strtotime($event["event_date"])); ?><br>
                    <span class="muted"><?php echo substr($event["start_time"], 0, 5) . " - " . substr($event["end_time"], 0, 5); ?></span>
                </td>
                <td><?php echo $event["venue"]; ?></td>
                <td class="actions">
                    <form style="display: inline;" action="/campusconnect/controllers/eventStatusControls.php" method="post"
                          onsubmit="return confirm('Cancel this event?');">
                        <input type="hidden" name="eventId" value="<?php echo $event["event_id"]; ?>">
                        <input type="hidden" name="status" value="CANCELLED">
                        <input type="submit" class="btn btn-small btn-danger" value="Cancel">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php endif; ?>
</div>

<?php require_once "../footer.php"; ?>
