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

// This page is the organizer's dashboard. The statistics are on it, so there
// is no separate dashboard page.
$pageTitle = "Organizer Dashboard";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <h1>Welcome, <?php echo $user["name"]; ?></h1>
    <p class="muted">Club organizer dashboard</p>
</div>

<?php if ($club == null): ?>

    <div class="flash flash-error">
        You do not have a club yet, so you cannot create events. Please contact the administrator.
    </div>

<?php else: ?>

    <div class="card">
        <h2><?php echo $club["club_name"]; ?></h2>
        <p class="muted"><?php echo $club["description"]; ?></p>

        <a class="btn" href="/campusconnect/views/organizer/myEvents.php">My Events</a>
        <a class="btn" href="/campusconnect/views/organizer/createEvent.php">Create Event</a>
        <a class="btn" href="/campusconnect/views/organizer/participants.php">Participants</a>
    </div>

    <div class="card">
        <h2>Event Statistics</h2>
        <p class="muted">Choose one of your events to see its numbers.</p>

        <label for="eventId">Event</label>
        <select id="eventId">
            <option value="0">-- choose an event --</option>
            <?php foreach ($events as $event): ?>
                <option value="<?php echo $event["event_id"]; ?>">
                    <?php echo $event["title"] . " (" . $event["event_date"] . ", " . $event["status"] . ")"; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div id="message"></div>

    <div id="statsArea" style="display: none;">
        <div class="tiles">
            <div class="tile">
                <div class="value" id="registered">0</div>
                <div class="label">Registrations</div>
            </div>
            <div class="tile">
                <div class="value" id="present">0</div>
                <div class="label">Present</div>
            </div>
            <div class="tile">
                <div class="value" id="percentage">0%</div>
                <div class="label">Attendance percentage</div>
            </div>
            <div class="tile">
                <div class="value" id="feedbackCount">0</div>
                <div class="label">Feedback received</div>
            </div>
            <div class="tile">
                <div class="value" id="averageRating">0</div>
                <div class="label">Average rating</div>
            </div>
        </div>
    </div>

<?php endif; ?>

<script src="/campusconnect/views/organizer/js/statistics.js"></script>

<?php require_once "../footer.php"; ?>

