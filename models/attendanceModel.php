<?php
require_once "dbConnect.php";

function findAttendance($registrationId)
{
    $sql = "SELECT * FROM attendance WHERE registration_id = ?";
    $result = executeQuery($sql, "i", $registrationId);
    return mysqli_fetch_assoc($result);
}

function saveAttendance($registrationId, $status)
{
    $existing = findAttendance($registrationId);

    if ($existing != null)
    {
        $sql = "UPDATE attendance SET attendance_status = ?, marked_at = CURRENT_TIMESTAMP WHERE registration_id = ?";
        return executeNonQuery($sql, "si", $status, $registrationId);
    }

    $sql = "INSERT INTO attendance (registration_id, attendance_status, marked_at) VALUES (?, ?, CURRENT_TIMESTAMP)";
    return executeNonQuery($sql, "is", $registrationId, $status);
}

?>
