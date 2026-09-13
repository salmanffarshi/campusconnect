<?php
require_once "../../controllers/authCheck.php";
require_once "../../models/registrationsModel.php";
require_once "../../models/eventsModel.php";
require_once "../../models/attendanceModel.php";
require_once "../../models/feedbackModel.php";
$user = checkRole("student");

$registrationId = 0;

if (isset($_GET["registrationId"]))
{
    $registrationId = (int) $_GET["registrationId"];
}

$registration = findRegistrationById($registrationId);

// The registration must belong to the logged in student.
if ($registration == null || $registration["student_id"] != $user["user_id"] || $registration["status"] != "REGISTERED")
{
    header("Location: /campusconnect/views/student/myRegistrations.php?generalErr=" . urlencode("That registration was not found."));
    exit();
}

$attendance = findAttendance($registrationId);

if ($attendance == null || $attendance["attendance_status"] != "PRESENT")
{
    header("Location: /campusconnect/views/student/myRegistrations.php?generalErr=" . urlencode("You can only give feedback for events you attended."));
    exit();
}

if (findFeedbackByRegistration($registrationId) != null)
{
    header("Location: /campusconnect/views/student/myRegistrations.php?generalErr=" . urlencode("You have already given feedback for this event."));
    exit();
}

$event = findEventById($registration["event_id"]);

$pageTitle = "Give Feedback";
require_once "../header.php";
?>

<div class="card card-narrow">
    <h1>Give Feedback</h1>
    <p class="muted">
        <?php echo $event["title"]; ?> &mdash; <?php echo date("d M Y", strtotime($event["event_date"])); ?>
    </p>

    <form action="/campusconnect/controllers/feedbackControls.php" method="post">

        <input type="hidden" name="registrationId" value="<?php echo $registrationId; ?>">

        <label for="rating">Rating</label>
        <select name="rating" id="rating">
            <option value="">Choose a rating</option>
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Good</option>
            <option value="3">3 - Okay</option>
            <option value="2">2 - Poor</option>
            <option value="1">1 - Very poor</option>
        </select>
        <span class="error">
            <?php
            if (isset($_GET["ratingErr"]))
            {
                echo $_GET["ratingErr"];
            }
            ?>
        </span>

        <label for="comment">Comment (optional)</label>
        <textarea name="comment" id="comment" rows="4"><?php if (isset($_GET["comment"])) { echo $_GET["comment"]; } ?></textarea>
        <span class="error">
            <?php
            if (isset($_GET["commentErr"]))
            {
                echo $_GET["commentErr"];
            }
            ?>
        </span>

        <input type="submit" class="btn" value="Submit Feedback">
        <a class="btn btn-link" href="/campusconnect/views/student/myRegistrations.php">Cancel</a>
    </form>
</div>

<?php require_once "../footer.php"; ?>
