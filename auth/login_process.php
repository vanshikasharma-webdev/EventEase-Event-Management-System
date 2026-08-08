<?php

/*
|--------------------------------------------------------------------------
| Start Session
|--------------------------------------------------------------------------
*/

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

require_once '../config/database.php';


/*
|--------------------------------------------------------------------------
| Allow Only POST Requests
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: login.php");
    exit();

}


/*
|--------------------------------------------------------------------------
| Get Login Credentials
|--------------------------------------------------------------------------
*/

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';


/*
|--------------------------------------------------------------------------
| Basic Input Validation
|--------------------------------------------------------------------------
*/

if ($email === '' || $password === '') {

    $_SESSION['login_error'] = "Email and password are required.";

    header("Location: login.php");
    exit();

}


/*
|--------------------------------------------------------------------------
| Find User
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        id,
        full_name,
        email,
        password,
        role,
        status
    FROM users
    WHERE email = ?
    LIMIT 1
");


if (!$stmt) {

    $_SESSION['login_error'] = "Unable to process login.";

    header("Location: login.php");
    exit();

}


$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| User Not Found
|--------------------------------------------------------------------------
*/

if ($result->num_rows !== 1) {

    $_SESSION['login_error'] = "Email not found.";

    $stmt->close();

    header("Location: login.php");
    exit();

}


$user = $result->fetch_assoc();

$stmt->close();


/*
|--------------------------------------------------------------------------
| Verify Password
|--------------------------------------------------------------------------
*/

if (!password_verify($password, $user['password'])) {

    $_SESSION['login_error'] = "Invalid Password.";

    header("Location: login.php");
    exit();

}


/*
|--------------------------------------------------------------------------
| Check Account Status
|--------------------------------------------------------------------------
*/

if (strcasecmp(trim($user['status']), 'Active') !== 0) {

    $_SESSION['login_error'] = "Your account is inactive.";

    header("Location: login.php");
    exit();

}


/*
|--------------------------------------------------------------------------
| Session Fixation Protection
|--------------------------------------------------------------------------
|
| Regenerate the session ID after successful authentication.
|
*/

session_regenerate_id(true);


/*
|--------------------------------------------------------------------------
| Clear Previous Authentication Data
|--------------------------------------------------------------------------
*/

unset(
    $_SESSION['user_id'],
    $_SESSION['user_name'],
    $_SESSION['user_email'],
    $_SESSION['user_role'],
    $_SESSION['admin_id'],
    $_SESSION['admin_name'],
    $_SESSION['admin_email'],
    $_SESSION['admin_role']
);


/*
|--------------------------------------------------------------------------
| Admin Login
|--------------------------------------------------------------------------
*/

if ($user['role'] === 'Admin') {

    $_SESSION['admin_id'] = $user['id'];
    $_SESSION['admin_name'] = $user['full_name'];
    $_SESSION['admin_email'] = $user['email'];
    $_SESSION['admin_role'] = 'Admin';

    header("Location: ../admin/dashboard.php");
    exit();

}


/*
|--------------------------------------------------------------------------
| User Login
|--------------------------------------------------------------------------
*/

$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['full_name'];
$_SESSION['user_email'] = $user['email'];
$_SESSION['user_role'] = 'User';


header("Location: ../user/dashboard.php");
exit();

?>