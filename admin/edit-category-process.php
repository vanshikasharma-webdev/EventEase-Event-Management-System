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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: manage-categories.php");
    exit();
}

$categoryId = (int) ($_POST['id'] ?? 0);
$categoryName = trim($_POST['category_name'] ?? '');
$description = trim($_POST['description'] ?? '');
$status = $_POST['status'] ?? '';

if (
    $categoryId <= 0 ||
    $categoryName === ''
) {
    $_SESSION['category_error'] =
        "Category name is required.";

    header("Location: manage-categories.php");
    exit();
}

$allowedStatuses = [
    'Active',
    'Inactive'
];

if (!in_array($status, $allowedStatuses, true)) {

    $_SESSION['category_error'] =
        "Invalid category status.";

    header("Location: manage-categories.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Check Duplicate Category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT id
    FROM categories
    WHERE category_name = ?
    AND id != ?
    LIMIT 1
");

$stmt->bind_param(
    "si",
    $categoryName,
    $categoryId
);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $_SESSION['category_error'] =
        "Another category with this name already exists.";

    header(
        "Location: edit-category.php?id=" .
        $categoryId
    );

    exit();
}


/*
|--------------------------------------------------------------------------
| Update Category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    UPDATE categories
    SET
        category_name = ?,
        description = ?,
        status = ?
    WHERE id = ?
");

$stmt->bind_param(
    "sssi",
    $categoryName,
    $description,
    $status,
    $categoryId
);

if ($stmt->execute()) {

    $_SESSION['category_success'] =
        "Category updated successfully.";

} else {

    $_SESSION['category_error'] =
        "Unable to update category.";

}

header("Location: manage-categories.php");
exit();

?>