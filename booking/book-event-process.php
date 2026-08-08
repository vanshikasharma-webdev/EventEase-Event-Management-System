<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

/* User Login Check */

if (!isset($_SESSION['user_id'])) {

    $_SESSION['error'] = "Please login first.";

    header("Location: ../auth/login.php");
    exit();
}

/* Allow POST Only */

if ($_SERVER['REQUEST_METHOD'] != 'POST') {

    header("Location: ../index.php");
    exit();
}

/* Validate Data */

$userId = $_SESSION['user_id'];

$eventId = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0;

if ($eventId <= 0) {

    $_SESSION['error'] = "Invalid Event.";

    header("Location: ../pages/events.php");
    exit();
}

/* Check Event Exists */

$stmt = $conn->prepare("
SELECT *
FROM events
WHERE id = ?
LIMIT 1
");

$stmt->bind_param("i", $eventId);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows == 0) {

    $_SESSION['error'] = "Event not found.";

    header("Location: ../pages/events.php");
    exit();
}

$event = $result->fetch_assoc();

/* Already Booked? */

$stmt = $conn->prepare("
SELECT id
FROM bookings
WHERE user_id = ?
AND event_id = ?
LIMIT 1
");

$stmt->bind_param("ii", $userId, $eventId);

$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows > 0) {

    $_SESSION['error'] = "You have already booked this event.";

    header("Location: book-event.php?id=" . $eventId);
    exit();
}

/* Save Booking */

$stmt = $conn->prepare("
INSERT INTO bookings
(user_id,event_id,total_amount)
VALUES
(?,?,?)
");

$stmt->bind_param(
    "iid",
    $userId,
    $eventId,
    $event['price']
);

if ($stmt->execute()) {

    $_SESSION['success'] = "Booking Successful!";

} else {

    $_SESSION['error'] = "Booking Failed.";

}

header("Location: my-bookings.php");

exit();