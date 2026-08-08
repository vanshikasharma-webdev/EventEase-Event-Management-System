<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: edit-profile.php");
    exit();
}

$userId = (int) $_SESSION['user_id'];

$fullName = trim($_POST['full_name'] ?? '');
$phone = trim($_POST['phone'] ?? '');

if ($fullName === '' || $phone === '') {

    $_SESSION['profile_error'] = "Name and phone are required.";

    header("Location: edit-profile.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Get Current Profile Image
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT profile_image
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    $_SESSION['profile_error'] = "User not found.";

    header("Location: edit-profile.php");
    exit();
}

$user = $result->fetch_assoc();

$currentImage = $user['profile_image'] ?? 'default.png';

$newImage = $currentImage;


/*
|--------------------------------------------------------------------------
| Check New Image
|--------------------------------------------------------------------------
*/

if (
    isset($_FILES['profile_image']) &&
    $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE
) {

    if ($_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {

        $_SESSION['profile_error'] =
            "Image upload failed. Error code: " .
            $_FILES['profile_image']['error'];

        header("Location: edit-profile.php");
        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | File Size
    |--------------------------------------------------------------------------
    */

    if ($_FILES['profile_image']['size'] > 2 * 1024 * 1024) {

        $_SESSION['profile_error'] =
            "Profile image must be less than 2MB.";

        header("Location: edit-profile.php");
        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | Check Actual Image Type
    |--------------------------------------------------------------------------
    */

    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);

    $mimeType = finfo_file(
        $fileInfo,
        $_FILES['profile_image']['tmp_name']
    );

    finfo_close($fileInfo);


    $allowedTypes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp'
    ];


    if (!isset($allowedTypes[$mimeType])) {

        $_SESSION['profile_error'] =
            "Only JPG, PNG and WEBP images are allowed.";

        header("Location: edit-profile.php");
        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | Upload Directory
    |--------------------------------------------------------------------------
    */

    $uploadDirectory = __DIR__ . '/../assets/uploads/';


    if (!is_dir($uploadDirectory)) {

        $_SESSION['profile_error'] =
            "Upload folder does not exist.";

        header("Location: edit-profile.php");
        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | Generate New File Name
    |--------------------------------------------------------------------------
    */

    $extension = $allowedTypes[$mimeType];

    $newImage =
        'profile_' .
        $userId .
        '_' .
        time() .
        '.' .
        $extension;


    $uploadPath = $uploadDirectory . $newImage;


    /*
    |--------------------------------------------------------------------------
    | Move Image
    |--------------------------------------------------------------------------
    */

    if (
        !move_uploaded_file(
            $_FILES['profile_image']['tmp_name'],
            $uploadPath
        )
    ) {

        $_SESSION['profile_error'] =
            "Image could not be saved. Check uploads folder permission.";

        header("Location: edit-profile.php");
        exit();
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Old Profile Image
    |--------------------------------------------------------------------------
    */

    if (
        !empty($currentImage) &&
        $currentImage !== 'default.png'
    ) {

        $oldImagePath =
            $uploadDirectory . basename($currentImage);

        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }
}


/*
|--------------------------------------------------------------------------
| Update Database
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    UPDATE users
    SET
        full_name = ?,
        phone = ?,
        profile_image = ?
    WHERE id = ?
");


$stmt->bind_param(
    "sssi",
    $fullName,
    $phone,
    $newImage,
    $userId
);


if ($stmt->execute()) {

    $_SESSION['user_name'] = $fullName;

    $_SESSION['profile_success'] =
        "Profile updated successfully.";

    header("Location: profile.php");
    exit();
}


$_SESSION['profile_error'] =
    "Profile update failed. Please try again.";

header("Location: edit-profile.php");
exit();

?>