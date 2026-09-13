<?php
require_once "dbConnect.php";

function getAllCategories()
{
    $sql = "SELECT * FROM categories ORDER BY category_name";
    $result = executeQuery($sql);
    return fetchAllRows($result);
}

function findCategoryById($categoryId)
{
    $sql = "SELECT * FROM categories WHERE category_id = ?";
    $result = executeQuery($sql, "i", $categoryId);
    return mysqli_fetch_assoc($result);
}

?>
