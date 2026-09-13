<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/clubsModel.php";
require_once "../../models/eventsModel.php";
$user = checkRole("organizer");
$club = findClubByOrganizer($user["user_id"]);

$events = array();

if ($club != null)
{
    $events = getEventsByClub($club["club_id"]);
}

$pageTitle = "My Events";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <h1>My Events</h1>

    <?php if ($club == null): ?>

        <p class="error">You do not have a club yet. Please contact the administrator.</p>

    <?php else: ?>

        <p class="muted">Club: <strong><?php echo $club["club_name"]; ?></strong></p>
        <a class="btn" href="/campusconnect/views/organizer/createEvent.php">+ Create Event</a>

        <?php if (count($events) == 0): ?>

            <p class="muted">You have not created any events yet.</p>

        <?php else: ?>

        <div class="table-wrap" style="margin-top: 16px;">
            <table>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Date &amp; Time</th>
                    <th>Venue</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

                <?php foreach ($events as $event): ?>
                <tr>
                    <td><?php echo $event["title"]; ?></td>
                    <td><?php echo $event["category_name"]; ?></td>
                    <td>
                        <?php echo date("d M Y", strtotime($event["event_date"])); ?><br>
                        <span class="muted"><?php echo substr($event["start_time"], 0, 5) . " - " . substr($event["end_time"], 0, 5); ?></span>
                    </td>
                    <td><?php echo $event["venue"]; ?></td>
                    <td><?php echo $event["registered_count"] . " / " . $event["capacity"]; ?></td>
                    <td><span class="badge badge-<?php echo strtolower($event["status"]); ?>"><?php echo $event["status"]; ?></span></td>
                    <td class="actions">
                        <?php if ($event["status"] == "PENDING" || $event["status"] == "REJECTED"): ?>
                            <a class="btn btn-small" href="/campusconnect/views/organizer/editEvent.php?eventId=<?php echo $event["event_id"]; ?>">Edit</a>
                        <?php endif; ?>

                        <?php if ($event["status"] == "APPROVED"): ?>
                            <a class="btn btn-small" href="/campusconnect/views/organizer/participants.php?eventId=<?php echo $event["event_id"]; ?>">Participants</a>
                        <?php endif; ?>

                        <?php if ($event["status"] == "PENDING" || $event["status"] == "APPROVED"): ?>
                            <form style="display: inline;" action="/campusconnect/controllers/cancelEventControls.php" method="post"
                                  onsubmit="return confirm('Cancel this event? Students will not be able to register any more.');">
                                <input type="hidden" name="eventId" value="<?php echo $event["event_id"]; ?>">
                                <input type="submit" class="btn btn-small btn-danger" value="Cancel">
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <?php endif; ?>

    <?php endif; ?>
</div>

<?php require_once "../footer.php"; ?>
