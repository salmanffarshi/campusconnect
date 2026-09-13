<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/clubsModel.php";
require_once "../../models/eventsModel.php";
require_once "../../models/registrationsModel.php";
$user = checkRole("organizer");
$club = findClubByOrganizer($user["user_id"]);

$events = array();

if ($club != null)
{
    $events = getEventsByClub($club["club_id"]);
}

$eventId = 0;

if (isset($_GET["eventId"]))
{
    $eventId = (int) $_GET["eventId"];
}

$selectedEvent = null;
$participants = array();

if ($eventId > 0)
{
    $selectedEvent = findEventById($eventId);

   if ($club == null || $selectedEvent == null || $selectedEvent["club_id"] != $club["club_id"])
    {
        header("Location: /campusconnect/views/organizer/participants.php?generalErr=" . urlencode("You can only see participants of your own events."));
        exit();
    }

    $participants = getParticipants($eventId);
}

$pageTitle = "Participants";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <h1>Participants</h1>

    <?php if ($club == null): ?>

        <p class="error">You do not have a club yet. Please contact the administrator.</p>

    <?php else: ?>

        <form method="get" action="/campusconnect/views/organizer/participants.php">
            <label for="eventId">Choose an event</label>
            <select name="eventId" id="eventId" onchange="this.form.submit()">
                <option value="0">-- choose an event --</option>
                <?php foreach ($events as $event): ?>
                    <option value="<?php echo $event["event_id"]; ?>" <?php if ($event["event_id"] == $eventId) { echo "selected"; } ?>>
                        <?php echo $event["title"] . " (" . $event["event_date"] . ", " . $event["status"] . ")"; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

    <?php endif; ?>
</div>

<?php if ($selectedEvent != null): ?>

<div class="card">
    <h2><?php echo $selectedEvent["title"]; ?></h2>
    <p class="muted">
        <?php echo date("d M Y", strtotime($selectedEvent["event_date"])); ?>,
        <?php echo substr($selectedEvent["start_time"], 0, 5) . " - " . substr($selectedEvent["end_time"], 0, 5); ?>,
        <?php echo $selectedEvent["venue"]; ?>
    </p>

    <div class="tiles">
        <div class="tile">
            <div class="value"><?php echo count($participants); ?></div>
            <div class="label">Registered participants</div>
        </div>
    </div>

    <div id="message" style="margin-top: 16px;"></div>

    <?php if (count($participants) == 0): ?>

        <p class="muted">Nobody has registered for this event yet.</p>

    <?php else: ?>

    <div class="table-wrap">
        <table>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Email</th>
                <th>Registered On</th>
                <th>Attendance</th>
                <th>Mark</th>
            </tr>

            <?php $number = 1; ?>
            <?php foreach ($participants as $person): ?>
            <tr>
                <td><?php echo $number; ?></td>
                <td><?php echo $person["name"]; ?></td>
                <td><?php echo $person["email"]; ?></td>
                <td><?php echo date("d M Y", strtotime($person["registered_at"])); ?></td>
                <td id="status-<?php echo $person["registration_id"]; ?>">
                    <?php if ($person["attendance_status"] == "PRESENT"): ?>
                        <span class="badge badge-approved">Present</span>
                    <?php elseif ($person["attendance_status"] == "ABSENT"): ?>
                        <span class="badge badge-rejected">Absent</span>
                    <?php else: ?>
                        <span class="muted">Not marked</span>
                    <?php endif; ?>
                </td>
                <td class="actions">
                    <button class="btn btn-small btn-green" onclick="markAttendance(<?php echo $person["registration_id"]; ?>, 'PRESENT')">Present</button>
                    <button class="btn btn-small btn-danger" onclick="markAttendance(<?php echo $person["registration_id"]; ?>, 'ABSENT')">Absent</button>
                </td>
            </tr>
            <?php $number = $number + 1; ?>
            <?php endforeach; ?>
        </table>
    </div>

    <?php endif; ?>
</div>

<script src="/campusconnect/views/organizer/js/participants.js"></script>

<?php endif; ?>

<?php require_once "../footer.php"; ?>
