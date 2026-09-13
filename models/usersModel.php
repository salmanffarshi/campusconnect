<?php
require_once "dbConnect.php";

function findUserByEmail($email){
    $sql = "SELECT * FROM users WHERE email = ?"; 
    $result = executeQuery($sql, "s", $email); 
    return mysqli_fetch_assoc($result);
}

function findUserById($userId) { 
    $sql = "SELECT * FROM users WHERE user_id = ?"; 
    $result = executeQuery($sql, "i", $userId); 
    return mysqli_fetch_assoc($result); 
}

function emailExists($email){
    $user = findUserByEmail($email);

    if ($user == null)
    {
        return false;
    }

    return true;
}

function createStudent($name, $email, $passwordHash){
    $sql = "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'student', 'active')";

    return executeInsert($sql, "sss", $name, $email, $passwordHash);
}

function updateProfile($userId, $name, $email){
    $sql = "UPDATE users SET name = ?, email = ? WHERE user_id = ?";
    return executeNonQuery($sql, "ssi", $name, $email, $userId);
}

function updatePassword($userId, $passwordHash){
    $sql = "UPDATE users SET password = ? WHERE user_id = ?";
    return executeNonQuery($sql, "si", $passwordHash, $userId);
}

function deactivateUser($userId){
    $sql = "UPDATE users SET status = 'inactive' WHERE user_id = ?";
    return executeNonQuery($sql, "i", $userId);
}



function setUserStatus($userId, $status){
    $sql = "UPDATE users SET status = ? WHERE user_id = ?";
    return executeNonQuery($sql, "si", $status, $userId);
}

// Admin user list. $search matches the name or email. An empty $role means every role.
function searchUsers($search, $role)
{
    $like = "%" . $search . "%";

    if ($role == "")
    {
        $sql = "SELECT user_id, name, email, role, status, created_at FROM users
                WHERE name LIKE ? OR email LIKE ?
                ORDER BY created_at DESC";
        $result = executeQuery($sql, "ss", $like, $like);
    }
    else
    {
        $sql = "SELECT user_id, name, email, role, status, created_at FROM users
                WHERE (name LIKE ? OR email LIKE ?) AND role = ?
                ORDER BY created_at DESC";
        $result = executeQuery($sql, "sss", $like, $like, $role);
    }

    return fetchAllRows($result);
}

function createOrganizer($name, $email, $passwordHash)
{
    $sql = "INSERT INTO users (name, email, password, role, status) VALUES (?, ?, ?, 'organizer', 'active')";
    return executeInsert($sql, "sss", $name, $email, $passwordHash);
}

?>