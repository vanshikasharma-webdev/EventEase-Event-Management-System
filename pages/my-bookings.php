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

$userId = (int) $_SESSION['user_id'];


// Fetch user's bookings
$stmt = $conn->prepare("
    SELECT
        bookings.id,
        bookings.booking_date,
        bookings.total_amount,
        bookings.booking_status,

        events.title,
        events.event_date,
        events.event_time,
        events.venue

    FROM bookings

    INNER JOIN events
        ON bookings.event_id = events.id

    WHERE bookings.user_id = ?

    ORDER BY bookings.booking_date DESC
");

$stmt->bind_param("i", $userId);
$stmt->execute();

$result = $stmt->get_result();


require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="text-white fw-bold">
            My Bookings 🎫
        </h2>

        <a
            href="events.php"
            class="btn btn-info"
        >
            Browse Events
        </a>

    </div>


    <?php if ($result->num_rows > 0): ?>

        <div class="table-responsive">

            <table class="table table-dark table-bordered align-middle">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Event</th>

                        <th>Date</th>

                        <th>Time</th>

                        <th>Venue</th>

                        <th>Booking Date</th>

                        <th>Amount</th>

                        <th>Status</th>

                    </tr>

                </thead>


                <tbody>

                    <?php while ($booking = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?= $booking['id']; ?>
                            </td>


                            <td class="fw-semibold">

                                <?= htmlspecialchars(
                                    $booking['title']
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $booking['event_date']
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $booking['event_time']
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $booking['venue']
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $booking['booking_date']
                                ); ?>

                            </td>


                            <td class="text-success fw-bold">

                                ₹<?= number_format(
                                    $booking['total_amount'],
                                    2
                                ); ?>

                            </td>


                            <td>

                                <?php if (
                                    $booking['booking_status']
                                    === 'Confirmed'
                                ): ?>

                                    <span class="badge bg-success">
                                        Confirmed
                                    </span>

                                <?php elseif (
                                    $booking['booking_status']
                                    === 'Cancelled'
                                ): ?>

                                    <span class="badge bg-danger">
                                        Cancelled
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-warning text-dark">
                                        Pending
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>


    <?php else: ?>

        <div class="glass rounded-4 p-5 text-center">

            <h4 class="text-white mb-3">
                No Bookings Yet 🎫
            </h4>

            <p class="text-secondary">
                You haven't booked any events yet.
            </p>

            <a
                href="events.php"
                class="btn btn-info"
            >
                Explore Events
            </a>

        </div>

    <?php endif; ?>

</div>


<?php

require_once '../includes/footer.php';

?>