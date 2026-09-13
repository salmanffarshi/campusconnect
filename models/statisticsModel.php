<?php
require_once "dbConnect.php";

function getCount($sql, $types = "", ...$values)
{
    $result = executeQuery($sql, $types, ...$values);
    $row = mysqli_fetch_assoc($result);
    return (int) $row["total"];
}

function getEventStatistics($eventId)
{
    $registered = getCount("SELECT COUNT(*) AS total FROM event_registrations
                            WHERE event_id = ? AND status = 'REGISTERED'", "i", $eventId);

    $present = getCount("SELECT COUNT(*) AS total FROM attendance a
                         JOIN event_registrations r ON r.registration_id = a.registration_id
                         WHERE r.event_id = ? AND r.status = 'REGISTERED' AND a.attendance_status = 'PRESENT'", "i", $eventId);

    $percentage = 0;

    if ($registered > 0)
    {
        $percentage = round(($present / $registered) * 100, 1);
    }

    $feedbackCount = getCount("SELECT COUNT(*) AS total FROM feedback f
                               JOIN event_registrations r ON r.registration_id = f.registration_id
                               WHERE r.event_id = ?", "i", $eventId);

    $result = executeQuery("SELECT AVG(f.rating) AS average FROM feedback f
                            JOIN event_registrations r ON r.registration_id = f.registration_id
                            WHERE r.event_id = ?", "i", $eventId);
    $row = mysqli_fetch_assoc($result);

    $average = 0;

    if ($row["average"] != null)
    {
        $average = round($row["average"], 1);
    }

    return array(
        "registered" => $registered,
        "present" => $present,
        "attendancePercentage" => $percentage,
        "feedbackCount" => $feedbackCount,
        "averageRating" => $average
    );
}

function getSystemStatistics()
{
    return array(
        "totalUsers" => getCount("SELECT COUNT(*) AS total FROM users"),
        "totalStudents" => getCount("SELECT COUNT(*) AS total FROM users WHERE role = 'student'"),
        "totalOrganizers" => getCount("SELECT COUNT(*) AS total FROM users WHERE role = 'organizer'"),
        "totalEvents" => getCount("SELECT COUNT(*) AS total FROM events"),
        "totalRegistrations" => getCount("SELECT COUNT(*) AS total FROM event_registrations WHERE status = 'REGISTERED'")
    );
}

?>
