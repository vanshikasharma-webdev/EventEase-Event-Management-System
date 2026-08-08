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
| Check User ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {

    header("Location: manage-users.php");
    exit();

}


$userId = $_GET['id'];



/*
|--------------------------------------------------------------------------
| Get Current Status
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT status
    FROM users
    WHERE id = ?
");


$stmt->bind_param(
    "i",
    $userId
);


$stmt->execute();


$result = $stmt->get_result();



if ($result->num_rows === 1) {


    $user = $result->fetch_assoc();



    if ($user['status'] === 'Active') {

        $newStatus = 'Inactive';

    } else {

        $newStatus = 'Active';

    }



    /*
    Update Status
    */


    $update = $conn->prepare("
        UPDATE users
        SET status = ?
        WHERE id = ?
    ");



    $update->bind_param(
        "si",
        $newStatus,
        $userId
    );



    $update->execute();



}



header("Location: manage-users.php");
exit();


?>