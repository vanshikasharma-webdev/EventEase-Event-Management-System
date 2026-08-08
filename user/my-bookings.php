<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../includes/auth-check.php';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$userId = $_SESSION['user_id'];


$stmt = $conn->prepare("
SELECT
    bookings.*,
    events.title,
    events.image,
    events.event_date,
    events.venue,
    events.price
FROM bookings
INNER JOIN events
ON bookings.event_id = events.id
WHERE bookings.user_id = ?
ORDER BY bookings.booking_date DESC
");

$stmt->bind_param("i", $userId);

$stmt->execute();

$result = $stmt->get_result();

?>

<section class="py-5">

<div class="container">

<h1 class="text-white mb-5">

My Bookings

</h1>

<div class="row">

<?php if($result->num_rows>0): ?>

<?php while($booking=$result->fetch_assoc()): ?>

<div class="col-lg-6 mb-4">

<div class="glass p-4 h-100">

<img
src="../assets/uploads/<?php echo htmlspecialchars($booking['image']); ?>"
class="img-fluid rounded mb-3 event-image"
alt="Event">

<h3>

<?php echo htmlspecialchars($booking['title']); ?>

</h3>

<p>

<strong>Date:</strong>

<?php echo $booking['event_date']; ?>

</p>

<p>

<strong>Venue:</strong>

<?php echo htmlspecialchars($booking['venue']); ?>

</p>

<p>

<strong>Price:</strong>

₹<?php echo $booking['price']; ?>

</p>

<p>

<strong>Status:</strong>

<span class="badge bg-warning text-dark">

<?php echo htmlspecialchars($booking['booking_status']); ?>

</span>

</p>

<p>

<strong>Booked On:</strong>

<?php echo $booking['booking_date']; ?>

</p>

</div>

</div>

<?php endwhile; ?>

<?php else: ?>

<div class="col-12">

<div class="alert alert-info">

You haven't booked any events yet.

</div>

</div>

<?php endif; ?>

</div>

</div>

</section>

<?php
require_once '../includes/footer.php';
?>