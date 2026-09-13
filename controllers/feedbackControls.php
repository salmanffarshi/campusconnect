<?php

require_once "authCheck.php";
require_once "../models/registrationsModel.php";
require_once "../models/attendanceModel.php";
require_once "../models/feedbackModel.php";

$user = checkRole("student");

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $registrationId = (int) $_POST["registrationId"];
    $rating = trim($_POST["rating"]);
    $comment = trim($_POST["comment"]);

    $registration = findRegistrationById($registrationId);

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

    $hasErr = false;
    $ratingErr = "";
    $commentErr = "";

    if ($rating == "")
    {
        $ratingErr = "Please choose a rating.";
        $hasErr = true;
    }
    else if (filter_var($rating, FILTER_VALIDATE_INT, array("options" => array("min_range" => 1, "max_range" => 5))) === false)
    {
        $ratingErr = "Rating must be a number from 1 to 5.";
        $hasErr = true;
    }

    if (strlen($comment) > 1000)
    {
        $commentErr = "Comment cannot be longer than 1000 letters.";
        $hasErr = true;
    }

    if ($hasErr)
    {
        $url = "Location: /campusconnect/views/student/feedback.php?registrationId=" . $registrationId
             . "&rating=" . urlencode($rating)
             . "&comment=" . urlencode($comment)
             . "&ratingErr=" . urlencode($ratingErr)
             . "&commentErr=" . urlencode($commentErr);
        header($url);
        exit();
    }

    // An empty comment is saved as NULL.
    if ($comment == "")
    {
        $comment = null;
    }

    createFeedback($registrationId, (int) $rating, $comment);

    header("Location: /campusconnect/views/student/myRegistrations.php?generalMessage=" . urlencode("Thank you for your feedback!"));
    exit();
}

?>
