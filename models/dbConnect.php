<?php
$serverName="localhost";
$userName="root";
$password="";
$db="campusconnect";

function dbConnection()
{
    global $serverName;
    global $userName;
    global $password;
    global $db;
    $conn=mysqli_connect($serverName, $userName, $password, $db);

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


?>