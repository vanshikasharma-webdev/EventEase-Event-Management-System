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
| Allow Only POST
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: add-event.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Get Form Data
|--------------------------------------------------------------------------
*/

$categoryId = (int) ($_POST['category_id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$description = trim($_POST['description'] ?? '');
$venue = trim($_POST['venue'] ?? '');
$eventDate = $_POST['event_date'] ?? '';
$eventTime = $_POST['event_time'] ?? '';
$price = $_POST['price'] ?? '';
$availableSeats = (int) ($_POST['available_seats'] ?? 0);
$status = $_POST['status'] ?? 'Upcoming';


/*
|--------------------------------------------------------------------------
| Validate Required Fields
|--------------------------------------------------------------------------
*/

if (
    $categoryId <= 0 ||
    $title === '' ||
    $description === '' ||
    $venue === '' ||
    $eventDate === '' ||
    $eventTime === '' ||
    $price === '' ||
    $availableSeats <= 0
) {

    $_SESSION['event_error'] =
        "All required fields must be filled.";

    header("Location: add-event.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Validate Price
|--------------------------------------------------------------------------
*/

if (!is_numeric($price) || $price < 0) {

    $_SESSION['event_error'] =
        "Please enter a valid event price.";

    header("Location: add-event.php");
    exit();
}

$price = (float) $price;


/*
|--------------------------------------------------------------------------
| Validate Status
|--------------------------------------------------------------------------
*/

$allowedStatuses = [
    'Upcoming',
    'Completed',
    'Cancelled'
];

if (!in_array($status, $allowedStatuses, true)) {

    $_SESSION['event_error'] =
        "Invalid event status.";

    header("Location: add-event.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Verify Category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT id
    FROM categories
    WHERE id = ?
    AND status = 'Active'
    LIMIT 1
");

$stmt->bind_param("i", $categoryId);

$stmt->execute();

$categoryResult = $stmt->get_result();

if ($categoryResult->num_rows !== 1) {

    $_SESSION['event_error'] =
        "Selected category is not available.";

    header("Location: add-event.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Image Upload
|--------------------------------------------------------------------------
*/

$imageName = '';


if (
    !isset($_FILES['image']) ||
    $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE
) {

    $_SESSION['event_error'] =
        "Please select an event image.";

    header("Location: add-event.php");
    exit();
}


if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {

    $_SESSION['event_error'] =
        "Event image upload failed.";

    header("Location: add-event.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Validate Image Type
|--------------------------------------------------------------------------
*/

$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];


$fileInfo = finfo_open(FILEINFO_MIME_TYPE);

$mimeType = finfo_file(
    $fileInfo,
    $_FILES['image']['tmp_name']
);

finfo_close($fileInfo);


if (!isset($allowedTypes[$mimeType])) {

    $_SESSION['event_error'] =
        "Only JPG, PNG and WEBP images are allowed.";

    header("Location: add-event.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Upload Directory
|--------------------------------------------------------------------------
*/

$uploadDirectory = __DIR__ . '/../assets/uploads/';


if (!is_dir($uploadDirectory)) {

    $_SESSION['event_error'] =
        "Upload directory does not exist.";

    header("Location: add-event.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Generate Unique Image Name
|--------------------------------------------------------------------------
*/

$extension = $allowedTypes[$mimeType];

$imageName =
    'event_' .
    time() .
    '_' .
    bin2hex(random_bytes(4)) .
    '.' .
    $extension;


$uploadPath = $uploadDirectory . $imageName;


/*
|--------------------------------------------------------------------------
| Move Uploaded Image
|--------------------------------------------------------------------------
*/

if (
    !move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $uploadPath
    )
) {

    $_SESSION['event_error'] =
        "Unable to save event image.";

    header("Location: add-event.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Insert Event
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    INSERT INTO events
    (
        category_id,
        title,
        description,
        venue,
        event_date,
        event_time,
        price,
        available_seats,
        image,
        status
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");


$stmt->bind_param(
    "isssssdiss",
    $categoryId,
    $title,
    $description,
    $venue,
    $eventDate,
    $eventTime,
    $price,
    $availableSeats,
    $imageName,
    $status
);


if ($stmt->execute()) {

    $_SESSION['event_success'] =
        "Event added successfully.";

    header("Location: manage-events.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Database Insert Failed
|--------------------------------------------------------------------------
*/

if (file_exists($uploadPath)) {
    unlink($uploadPath);
}


$_SESSION['event_error'] =
    "Unable to add event. Please try again.";

header("Location: add-event.php");
exit();

?>