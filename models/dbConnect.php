<?php

date_default_timezone_set("Asia/Dhaka");

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "campusconnect");

function dbConnection()
{
    global $serverName;
    global $userName;
    global $dbpassword;
    global $db;
    $conn=mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    if($conn)
    {
        mysqli_set_charset($conn, "utf8mb4");
        return $conn;
    }
    else
    {
        echo "connection failed".mysqli_connect_error();
    }
}


function executeQuery($sql, $types = "", ...$values)
{
    $conn = dbConnection();

    $stmt = mysqli_prepare($conn, $sql);

    if ($types != "")
    {
        mysqli_stmt_bind_param($stmt, $types, ...$values);
    }

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $result;
}

function executeNonQuery($sql, $types = "", ...$values)
{
    $conn = dbConnection();

    $stmt = mysqli_prepare($conn, $sql);

    if ($types != "")
    {
        mysqli_stmt_bind_param($stmt, $types, ...$values);
    }

    $ok = mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $ok;
}

function executeInsert($sql, $types = "", ...$values)
{
    $conn = dbConnection();

    $stmt = mysqli_prepare($conn, $sql);

    if ($types != "")
    {
        mysqli_stmt_bind_param($stmt, $types, ...$values);
    }

    $ok = mysqli_stmt_execute($stmt);

    $insertId = 0;

    if ($ok)
    {
        $insertId = mysqli_insert_id($conn);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);

    return $insertId;
}

function fetchAllRows($result)
{
    $rows = array();

    while ($row = mysqli_fetch_assoc($result))
    {
        $rows[] = $row;
    }

    return $rows;
}


?>