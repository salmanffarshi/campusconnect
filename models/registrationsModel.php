<?php
require_once "dbConnect.php";

function findRegistration($eventId, $studentId)
{
    $sql = "SELECT * FROM event_registrations WHERE event_id = ? AND student_id = ?";
    $result = executeQuery($sql, "ii", $eventId, $studentId);
    return mysqli_fetch_assoc($result);
}

function findRegistrationById($registrationId)
{
    $sql = "SELECT * FROM event_registrations WHERE registration_id = ?";
    $result = executeQuery($sql, "i", $registrationId);
    return mysqli_fetch_assoc($result);
}

// Only REGISTERED rows count. A cancelled registration frees its seat.
function countActiveRegistrations($eventId)
{
    $sql = "SELECT COUNT(*) AS total FROM event_registrations WHERE event_id = ? AND status = 'REGISTERED'";
    $result = executeQuery($sql, "i", $eventId);
    $row = mysqli_fetch_assoc($result);
    return (int) $row["total"];
}

function findTimeClash($studentId, $eventId, $eventDate, $startTime, $endTime)
{
    $sql = "SELECT e.event_id, e.title, e.start_time, e.end_time
            FROM event_registrations r
            JOIN events e ON e.event_id = r.event_id
            WHERE r.student_id = ?
              AND r.status = 'REGISTERED'
              AND e.status = 'APPROVED'
              AND e.event_id != ?
              AND e.event_date = ?
              AND ? < e.end_time
              AND ? > e.start_time
            LIMIT 1";
    $result = executeQuery($sql, "iisss", $studentId, $eventId, $eventDate, $startTime, $endTime);
    return mysqli_fetch_assoc($result);
}

function createRegistration($eventId, $studentId)
{
    $sql = "INSERT INTO event_registrations (event_id, student_id, status) VALUES (?, ?, 'REGISTERED')";
    return executeInsert($sql, "ii", $eventId, $studentId);
}

function reactivateRegistration($registrationId)
{
    $sql = "UPDATE event_registrations SET status = 'REGISTERED', registered_at = CURRENT_TIMESTAMP WHERE registration_id = ?";
    return executeNonQuery($sql, "i", $registrationId);
}

function cancelRegistration($registrationId)
{
    $sql = "UPDATE event_registrations SET status = 'CANCELLED' WHERE registration_id = ?";
    return executeNonQuery($sql, "i", $registrationId);
}

function getRegistrationsByStudent($studentId)
{
    $sql = "SELECT r.registration_id, r.status AS registration_status, r.registered_at,
                   e.event_id, e.title, e.event_date, e.start_time, e.end_time, e.venue, e.status AS event_status,
                   c.club_name,
                   a.attendance_status,
                   f.rating
            FROM event_registrations r
            JOIN events e ON e.event_id = r.event_id
            JOIN clubs c ON c.club_id = e.club_id
            LEFT JOIN attendance a ON a.registration_id = r.registration_id
            LEFT JOIN feedback f ON f.registration_id = r.registration_id
            WHERE r.student_id = ?
            ORDER BY e.event_date DESC, e.start_time";
    $result = executeQuery($sql, "i", $studentId);
    return fetchAllRows($result);
}
function getParticipants($eventId)
{
    $sql = "SELECT r.registration_id, r.registered_at,
                   u.user_id, u.name, u.email,
                   a.attendance_status, a.marked_at
            FROM event_registrations r
            JOIN users u ON u.user_id = r.student_id
            LEFT JOIN attendance a ON a.registration_id = r.registration_id
            WHERE r.event_id = ? AND r.status = 'REGISTERED'
            ORDER BY u.name";
    $result = executeQuery($sql, "i", $eventId);
    return fetchAllRows($result);
}

?>
