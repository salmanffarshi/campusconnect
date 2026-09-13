<?php
require_once "dbConnect.php";

function findEventById($eventId)
{
    $sql = "SELECT * FROM events WHERE event_id = ?";
    $result = executeQuery($sql, "i", $eventId);
    return mysqli_fetch_assoc($result);
}

function getEventsByClub($clubId)
{
    $sql = "SELECT e.*, cat.category_name,
                   (SELECT COUNT(*) FROM event_registrations r
                    WHERE r.event_id = e.event_id AND r.status = 'REGISTERED') AS registered_count
            FROM events e
            JOIN categories cat ON cat.category_id = e.category_id
            WHERE e.club_id = ?
            ORDER BY e.event_date DESC, e.start_time";
    $result = executeQuery($sql, "i", $clubId);
    return fetchAllRows($result);
}

function createEvent($clubId, $categoryId, $title, $description, $eventDate, $startTime, $endTime, $venue, $capacity)
{
    $sql = "INSERT INTO events (club_id, category_id, title, description, event_date, start_time, end_time, venue, capacity, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'PENDING')";
    return executeInsert($sql, "iissssssi", $clubId, $categoryId, $title, $description, $eventDate, $startTime, $endTime, $venue, $capacity);
}

function updateEvent($eventId, $categoryId, $title, $description, $eventDate, $startTime, $endTime, $venue, $capacity)
{
    $sql = "UPDATE events
            SET category_id = ?, title = ?, description = ?, event_date = ?, start_time = ?, end_time = ?,
                venue = ?, capacity = ?, status = 'PENDING'
            WHERE event_id = ?";
    return executeNonQuery($sql, "issssssii", $categoryId, $title, $description, $eventDate, $startTime, $endTime, $venue, $capacity, $eventId);
}

function setEventStatus($eventId, $status)
{
    $sql = "UPDATE events SET status = ? WHERE event_id = ?";
    return executeNonQuery($sql, "si", $status, $eventId);
}

?>
