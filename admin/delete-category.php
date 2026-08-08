<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

if (
    !isset($_SESSION['admin_id']) ||
    !isset($_SESSION['admin_role']) ||
    $_SESSION['admin_role'] !== 'Admin'
) {
    header("Location: login.php");
    exit();
}

$categoryId = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($categoryId <= 0) {

    $_SESSION['category_error'] =
        "Invalid category ID.";

    header("Location: manage-categories.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Check Whether Events Use This Category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT COUNT(*) AS total
    FROM events
    WHERE category_id = ?
");

$stmt->bind_param("i", $categoryId);
$stmt->execute();

$result = $stmt->get_result();

$count = $result->fetch_assoc()['total'];


if ($count > 0) {

    $_SESSION['category_error'] =
        "This category cannot be deleted because events are using it.";

    header("Location: manage-categories.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Delete Category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM categories
    WHERE id = ?
");

$stmt->bind_param("i", $categoryId);

if ($stmt->execute()) {

    $_SESSION['category_success'] =
        "Category deleted successfully.";

} else {

    $_SESSION['category_error'] =
        "Unable to delete category.";

}

header("Location: manage-categories.php");
exit();

?>