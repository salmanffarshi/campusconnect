<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/registrationsModel.php";
$user = checkRole("student");
$registrations = getRegistrationsByStudent($user["user_id"]);
$today = date("Y-m-d");
$pageTitle = "My Registrations";
require_once "../header.php";
require_once "../messages.php";
?>

<div class="card">
    <h1>My Registrations</h1>
    <div id="message"></div>

    <?php if (count($registrations) == 0): ?>

        <p class="muted">
            You have not registered for any event yet.
            <a href="/campusconnect/views/student/events.php">Browse events</a>
        </p>

    <?php else: ?>

    <div class="table-wrap">
        <table>
            <tr>
                <th>Event</th>
                <th>Club</th>
                <th>Date &amp; Time</th>
                <th>Venue</th>
                <th>Registration</th>
                <th>Attendance</th>
                <th>Feedback</th>
                <th>Action</th>
            </tr>

            <?php foreach ($registrations as $row): ?>
            <?php $isPast = $row["event_date"] < $today; ?>
            <tr>
                <td>
                    <?php if ($row["event_status"] == "APPROVED"): ?>
                        <a href="/campusconnect/views/student/eventDetails.php?eventId=<?php echo $row["event_id"]; ?>"><?php echo $row["title"]; ?></a>
                    <?php else: ?>
                        <?php echo $row["title"]; ?>
                        <br><span class="badge badge-cancelled">Event <?php echo strtolower($row["event_status"]); ?></span>
                    <?php endif; ?>
                </td>

                <td><?php echo $row["club_name"]; ?></td>

                <td>
                    <?php echo date("d M Y", strtotime($row["event_date"])); ?><br>
                    <span class="muted"><?php echo substr($row["start_time"], 0, 5) . " - " . substr($row["end_time"], 0, 5); ?></span>
                </td>

                <td><?php echo $row["venue"]; ?></td>

                <td>
                    <?php if ($row["registration_status"] == "REGISTERED"): ?>
                        <span class="badge badge-approved">Registered</span>
                    <?php else: ?>
                        <span class="badge badge-cancelled">Cancelled</span>
                    <?php endif; ?>
                </td>

                <td>
                    <?php if ($row["attendance_status"] == "PRESENT"): ?>
                        <span class="badge badge-approved">Present</span>
                    <?php elseif ($row["attendance_status"] == "ABSENT"): ?>
                        <span class="badge badge-rejected">Absent</span>
                    <?php else: ?>
                        <span class="muted">Not marked</span>
                    <?php endif; ?>
                </td>

                <td>
                    <?php if ($row["rating"] != null): ?>
                        <span class="stars"><?php echo str_repeat("&#9733;", $row["rating"]) . str_repeat("&#9734;", 5 - $row["rating"]); ?></span>
                    <?php elseif ($row["registration_status"] == "REGISTERED" && $row["attendance_status"] == "PRESENT"): ?>
                        <a class="btn btn-small" href="/campusconnect/views/student/feedback.php?registrationId=<?php echo $row["registration_id"]; ?>">Give Feedback</a>
                    <?php else: ?>
                        <span class="muted">-</span>
                    <?php endif; ?>
                </td>

                <td class="actions">
                    <?php if ($row["registration_status"] == "REGISTERED" && $isPast == false && $row["event_status"] == "APPROVED"): ?>
                        <button class="btn btn-small btn-danger" onclick="cancelRegistration(<?php echo $row["event_id"]; ?>)">Cancel</button>
                    <?php else: ?>
                        <span class="muted">-</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <?php endif; ?>
</div>

<script src="/campusconnect/views/student/js/registration.js"></script>

<?php require_once "../footer.php"; ?>
