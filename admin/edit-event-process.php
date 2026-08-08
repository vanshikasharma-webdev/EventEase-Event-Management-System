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
| Allow Only POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: manage-events.php");
    exit();

}



$id = $_POST['id'];

$title = trim($_POST['title']);
$category_id = $_POST['category_id'];
$description = trim($_POST['description']);
$venue = trim($_POST['venue']);
$event_date = $_POST['event_date'];
$event_time = $_POST['event_time'];
$price = $_POST['price'];
$available_seats = $_POST['available_seats'];
$status = $_POST['status'];




/*
|--------------------------------------------------------------------------
| Get Existing Image
|--------------------------------------------------------------------------
*/

$imageQuery = $conn->prepare("
    SELECT image
    FROM events
    WHERE id = ?
");

$imageQuery->bind_param("i", $id);

$imageQuery->execute();

$imageResult = $imageQuery->get_result();

$currentEvent = $imageResult->fetch_assoc();


$imageName = $currentEvent['image'];





/*
|--------------------------------------------------------------------------
| New Image Upload
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === 0
) {


    $allowedTypes = [
        'image/jpeg',
        'image/png',
        'image/webp'
    ];



    if (!in_array($_FILES['image']['type'], $allowedTypes)) {

        $_SESSION['event_error'] =
        "Only JPG, PNG and WEBP allowed.";

        header("Location: edit-event.php?id=".$id);
        exit();

    }




    $extension = pathinfo(
        $_FILES['image']['name'],
        PATHINFO_EXTENSION
    );


    $newImageName = time().".".$extension;


    $uploadPath =
    "../assets/uploads/".$newImageName;



    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $uploadPath
    );


    /*
    Remove old image
    */

    if (
        !empty($imageName) &&
        file_exists("../assets/uploads/".$imageName)
    ) {

        unlink("../assets/uploads/".$imageName);

    }


    $imageName = $newImageName;

}




/*
|--------------------------------------------------------------------------
| Update Event
|--------------------------------------------------------------------------
*/


$stmt = $conn->prepare("
UPDATE events SET

category_id=?,
title=?,
description=?,
venue=?,
event_date=?,
event_time=?,
price=?,
available_seats=?,
image=?,
status=?

WHERE id=?

");



$stmt->bind_param(
    "isssssdissi",
    $category_id,
    $title,
    $description,
    $venue,
    $event_date,
    $event_time,
    $price,
    $available_seats,
    $imageName,
    $status,
    $id
);



if ($stmt->execute()) {


    $_SESSION['event_success'] =
    "Event updated successfully.";


    header("Location: manage-events.php");

    exit();


}
else {


    $_SESSION['event_error'] =
    "Event update failed.";


    header("Location: edit-event.php?id=".$id);

    exit();

}


?>