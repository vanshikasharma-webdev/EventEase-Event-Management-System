<?php

/*
|--------------------------------------------------------------------------
| Session
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
| Allow Only POST Request
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

    header("Location: register.php");
    exit();

}


/*
|--------------------------------------------------------------------------
| Get Form Data Safely
|--------------------------------------------------------------------------
*/

$fullName = trim($_POST['full_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

$errors = [];


/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/


/* Full Name */

if ($fullName === '') {

    $errors[] = "Full Name is required.";

}


/* Email */

if (
    $email === '' ||
    !filter_var($email, FILTER_VALIDATE_EMAIL)
) {

    $errors[] = "Invalid email address.";

}


/* Phone */

if (!preg_match('/^[0-9]{10}$/', $phone)) {

    $errors[] = "Phone number must be exactly 10 digits.";

}


/* Password */

if (strlen($password) < 6) {

    $errors[] = "Password must contain at least 6 characters.";

}


/* Confirm Password */

if ($password !== $confirmPassword) {

    $errors[] = "Passwords do not match.";

}


/*
|--------------------------------------------------------------------------
| Duplicate Email Check
|--------------------------------------------------------------------------
*/

if (empty($errors)) {

    $stmt = $conn->prepare(
        "SELECT id FROM users WHERE email = ? LIMIT 1"
    );


    if (!$stmt) {

        $errors[] = "Unable to process registration right now.";

    } else {

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();


        if ($result && $result->num_rows > 0) {

            $errors[] = "Email already registered.";

        }


        $stmt->close();

    }

}


/*
|--------------------------------------------------------------------------
| Validation Failed
|--------------------------------------------------------------------------
*/

if (!empty($errors)) {

    $_SESSION['errors'] = $errors;


    $_SESSION['old'] = [

        'full_name' => $fullName,
        'email' => $email,
        'phone' => $phone

    ];


    header("Location: register.php");
    exit();

}


/*
|--------------------------------------------------------------------------
| Password Hash
|--------------------------------------------------------------------------
*/

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/*
|--------------------------------------------------------------------------
| Default User Values
|--------------------------------------------------------------------------
*/

$profileImage = "default.png";
$role = "User";
$status = "Active";


/*
|--------------------------------------------------------------------------
| Insert User
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    INSERT INTO users
    (
        full_name,
        email,
        phone,
        password,
        profile_image,
        role,
        status
    )
    VALUES
    (?, ?, ?, ?, ?, ?, ?)
");


if (!$stmt) {

    $_SESSION['errors'] = [
        "Something went wrong while creating your account."
    ];

    header("Location: register.php");
    exit();

}


$stmt->bind_param(
    "sssssss",
    $fullName,
    $email,
    $phone,
    $hashedPassword,
    $profileImage,
    $role,
    $status
);


/*
|--------------------------------------------------------------------------
| Execute Registration
|--------------------------------------------------------------------------
*/

if ($stmt->execute()) {

    $_SESSION['success'] = "Registration Successful! Please Login.";

} else {

    /*
    |--------------------------------------------------------------------------
    | Handle Duplicate Email / Database Error
    |--------------------------------------------------------------------------
    */

    if ($stmt->errno === 1062) {

        $_SESSION['errors'] = [
            "Email already registered."
        ];

    } else {

        $_SESSION['errors'] = [
            "Something went wrong while creating your account."
        ];

    }

}


$stmt->close();


/*
|--------------------------------------------------------------------------
| Redirect
|--------------------------------------------------------------------------
*/

header("Location: register.php");
exit();

?>