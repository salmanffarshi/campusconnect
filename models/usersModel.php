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


?>