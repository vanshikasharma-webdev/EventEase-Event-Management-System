<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';


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


/*
|--------------------------------------------------------------------------
| Get Event ID
|--------------------------------------------------------------------------
*/

$eventId = isset($_GET['id']) ? (int) $_GET['id'] : 0;


if ($eventId <= 0) {

    $_SESSION['event_error'] =
        "Invalid event ID.";

    header("Location: manage-events.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Get Event Image
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT image
    FROM events
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $eventId);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows !== 1) {

    $_SESSION['event_error'] =
        "Event not found.";

    header("Location: manage-events.php");
    exit();
}


$event = $result->fetch_assoc();

$imageName = $event['image'];


/*
|--------------------------------------------------------------------------
| Delete Event
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    DELETE FROM events
    WHERE id = ?
");

$stmt->bind_param("i", $eventId);


if ($stmt->execute()) {


    /*
    |--------------------------------------------------------------------------
    | Delete Event Image
    |--------------------------------------------------------------------------
    */

    if (!empty($imageName)) {

        $imagePath =
            __DIR__ . '/../assets/uploads/' .
            basename($imageName);

        if (file_exists($imagePath)) {

            unlink($imagePath);

        }
    }


    $_SESSION['event_success'] =
        "Event deleted successfully.";

} else {

    $_SESSION['event_error'] =
        "Unable to delete event.";

}


header("Location: manage-events.php");
exit();

?>