<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| User Authentication
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {

    header("Location: ../auth/login.php");
    exit();

}


require_once '../config/database.php';


/*
|--------------------------------------------------------------------------
| Allow Only POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: change-password.php");
    exit();

}


$userId = (int) $_SESSION['user_id'];

$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';



/*
|--------------------------------------------------------------------------
| Check Empty Fields
|--------------------------------------------------------------------------
*/

if (
    $currentPassword === '' ||
    $newPassword === '' ||
    $confirmPassword === ''
) {

    $_SESSION['password_error'] =
        "All password fields are required.";

    header("Location: change-password.php");
    exit();

}



/*
|--------------------------------------------------------------------------
| Check New Password Length
|--------------------------------------------------------------------------
*/

if (strlen($newPassword) < 8) {

    $_SESSION['password_error'] =
        "New password must contain at least 8 characters.";

    header("Location: change-password.php");
    exit();

}



/*
|--------------------------------------------------------------------------
| Confirm Password
|--------------------------------------------------------------------------
*/

if ($newPassword !== $confirmPassword) {

    $_SESSION['password_error'] =
        "New password and confirm password do not match.";

    header("Location: change-password.php");
    exit();

}



/*
|--------------------------------------------------------------------------
| Get Current Password Hash
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT password
    FROM users
    WHERE id = ?
    LIMIT 1
");


$stmt->bind_param(
    "i",
    $userId
);


$stmt->execute();


$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    $_SESSION['password_error'] =
        "User account not found.";

    header("Location: change-password.php");
    exit();

}


$user = $result->fetch_assoc();

$currentPasswordHash = $user['password'];



/*
|--------------------------------------------------------------------------
| Verify Current Password
|--------------------------------------------------------------------------
*/

if (!password_verify($currentPassword, $currentPasswordHash)) {

    $_SESSION['password_error'] =
        "Current password is incorrect.";

    header("Location: change-password.php");
    exit();

}



/*
|--------------------------------------------------------------------------
| Prevent Same Password
|--------------------------------------------------------------------------
*/

if (password_verify($newPassword, $currentPasswordHash)) {

    $_SESSION['password_error'] =
        "New password must be different from your current password.";

    header("Location: change-password.php");
    exit();

}



/*
|--------------------------------------------------------------------------
| Hash New Password
|--------------------------------------------------------------------------
*/

$newPasswordHash = password_hash(
    $newPassword,
    PASSWORD_DEFAULT
);



/*
|--------------------------------------------------------------------------
| Update Password
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    UPDATE users
    SET password = ?
    WHERE id = ?
");


$stmt->bind_param(
    "si",
    $newPasswordHash,
    $userId
);



if ($stmt->execute()) {

    $_SESSION['password_success'] =
        "Password changed successfully.";

    header("Location: change-password.php");
    exit();

}



/*
|--------------------------------------------------------------------------
| Update Failed
|--------------------------------------------------------------------------
*/

$_SESSION['password_error'] =
    "Unable to change password. Please try again.";

header("Location: change-password.php");
exit();

?>