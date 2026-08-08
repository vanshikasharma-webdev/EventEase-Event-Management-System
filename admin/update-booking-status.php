<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| Admin Authentication
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['admin_id']) ||
    !isset($_SESSION['admin_role']) ||
    $_SESSION['admin_role'] !== 'Admin'
) {

    header("Location: login.php");
    exit();

}


require_once '../config/database.php';



/*
|--------------------------------------------------------------------------
| Check Parameters
|--------------------------------------------------------------------------
*/

if (
    !isset($_GET['id']) ||
    !isset($_GET['status'])
) {

    header("Location: manage-bookings.php");
    exit();

}



$bookingId = $_GET['id'];
$status = $_GET['status'];



/*
|--------------------------------------------------------------------------
| Validate Status
|--------------------------------------------------------------------------
*/

$allowedStatus = [
    'Pending',
    'Confirmed',
    'Cancelled'
];


if (!in_array($status, $allowedStatus)) {

    header("Location: manage-bookings.php");
    exit();

}



/*
|--------------------------------------------------------------------------
| Update Booking Status
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    UPDATE bookings
    SET booking_status = ?
    WHERE id = ?
");


$stmt->bind_param(
    "si",
    $status,
    $bookingId
);



$stmt->execute();



header("Location: manage-bookings.php");
exit();


?>