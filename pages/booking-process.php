<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';


// User must be logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}


// Only POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: events.php");
    exit();
}


$userId  = (int) $_SESSION['user_id'];
$eventId = isset($_POST['event_id']) ? (int) $_POST['event_id'] : 0;
$seats   = isset($_POST['seats']) ? (int) $_POST['seats'] : 0;


// Validate input
if ($eventId <= 0 || $seats <= 0) {
    header("Location: events.php");
    exit();
}


// Start transaction
$conn->begin_transaction();


try {

    // Get event
    $stmt = $conn->prepare("
        SELECT
            id,
            price,
            available_seats,
            status
        FROM events
        WHERE id = ?
        FOR UPDATE
    ");

    $stmt->bind_param("i", $eventId);
    $stmt->execute();

    $result = $stmt->get_result();


    if ($result->num_rows !== 1) {
        throw new Exception("Event not found.");
    }


    $event = $result->fetch_assoc();


    // Check status
    if ($event['status'] !== 'Upcoming') {
        throw new Exception("This event is not available for booking.");
    }


    // Check seats
    if ($seats > $event['available_seats']) {
        throw new Exception("Not enough seats available.");
    }


    // Calculate amount
    $totalAmount = $event['price'] * $seats;


    // Insert booking
    $bookingStmt = $conn->prepare("
        INSERT INTO bookings
        (
            user_id,
            event_id,
            booking_date,
            total_amount,
            booking_status
        )
        VALUES
        (
            ?,
            ?,
            NOW(),
            ?,
            'Confirmed'
        )
    ");


    $bookingStmt->bind_param(
        "iid",
        $userId,
        $eventId,
        $totalAmount
    );


    if (!$bookingStmt->execute()) {
        throw new Exception("Booking could not be created.");
    }


    // Reduce available seats
    $updateStmt = $conn->prepare("
        UPDATE events
        SET available_seats = available_seats - ?
        WHERE id = ?
        AND available_seats >= ?
    ");


    $updateStmt->bind_param(
        "iii",
        $seats,
        $eventId,
        $seats
    );


    if (!$updateStmt->execute() || $updateStmt->affected_rows !== 1) {
        throw new Exception("Seats could not be updated.");
    }


    // Commit
    $conn->commit();


    $_SESSION['booking_success'] =
        "Booking confirmed successfully!";


    header("Location: booking-success.php");

    exit();


} catch (Exception $e) {

    // Rollback
    $conn->rollback();


    $_SESSION['booking_error'] = $e->getMessage();


    header(
        "Location: event-details.php?id=" . $eventId
    );

    exit();

}