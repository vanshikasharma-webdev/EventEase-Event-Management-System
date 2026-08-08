<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

/* Allow only POST requests */

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

/* Get form data */

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

/* Basic validation */

if ($email === '' || $password === '') {

    $_SESSION['login_error'] = "Please enter email and password.";

    header("Location: login.php");
    exit();
}

/* Find Admin */

$stmt = $conn->prepare("
    SELECT id, name, email, password
    FROM admins
    WHERE email = ?
    LIMIT 1
");

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();

/* Check Admin */

if ($result->num_rows === 1) {

    $admin = $result->fetch_assoc();

    /* Verify Password */

    if (password_verify($password, $admin['password'])) {

        /* Create Admin Session */

        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_name'] = $admin['name'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role'] = 'Admin';

        /* Redirect to Admin Dashboard */

        header("Location: dashboard.php");
        exit();

    } else {

        $_SESSION['login_error'] = "Invalid password.";

    }

} else {

    $_SESSION['login_error'] = "Admin account not found.";

}

header("Location: login.php");
exit();

?>