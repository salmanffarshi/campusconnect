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

function searchApprovedEvents($search, $categoryId)
{
    $like = "%" . $search . "%";
    $today = date("Y-m-d");

    $sql = "SELECT e.event_id, e.title, e.event_date, e.start_time, e.end_time, e.venue, e.capacity,
                   c.club_name, cat.category_name,
                   (SELECT COUNT(*) FROM event_registrations r
                    WHERE r.event_id = e.event_id AND r.status = 'REGISTERED') AS registered_count
            FROM events e
            JOIN clubs c ON c.club_id = e.club_id
            JOIN categories cat ON cat.category_id = e.category_id
            WHERE e.status = 'APPROVED'
              AND e.event_date >= ?
              AND (e.title LIKE ? OR e.venue LIKE ? OR c.club_name LIKE ?)";

    if ($categoryId > 0)
    {
        $sql = $sql . " AND e.category_id = ? ORDER BY e.event_date, e.start_time";
        $result = executeQuery($sql, "ssssi", $today, $like, $like, $like, $categoryId);
    }
    else
    {
        $sql = $sql . " ORDER BY e.event_date, e.start_time";
        $result = executeQuery($sql, "ssss", $today, $like, $like, $like);
    }

    return fetchAllRows($result);
}

function findEventDetails($eventId)
{
    $sql = "SELECT e.*, c.club_name, c.description AS club_description, cat.category_name,
                   u.name AS organizer_name, u.email AS organizer_email,
                   (SELECT COUNT(*) FROM event_registrations r
                    WHERE r.event_id = e.event_id AND r.status = 'REGISTERED') AS registered_count
            FROM events e
            JOIN clubs c ON c.club_id = e.club_id
            JOIN categories cat ON cat.category_id = e.category_id
            JOIN users u ON u.user_id = c.organizer_id
            WHERE e.event_id = ?";
    $result = executeQuery($sql, "i", $eventId);
    return mysqli_fetch_assoc($result);
}

function getEventsForAdmin($status)
{
    $sql = "SELECT e.event_id, e.title, e.event_date, e.start_time, e.end_time, e.venue, e.capacity, e.status,
                   c.club_name, cat.category_name
            FROM events e
            JOIN clubs c ON c.club_id = e.club_id
            JOIN categories cat ON cat.category_id = e.category_id";

    if ($status == "")
    {
        $sql = $sql . " ORDER BY e.event_date DESC, e.start_time";
        $result = executeQuery($sql);
    }
    else
    {
        $sql = $sql . " WHERE e.status = ? ORDER BY e.event_date, e.start_time";
        $result = executeQuery($sql, "s", $status);
    }

    return fetchAllRows($result);
}

?>
