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


// Get event ID and seats
$eventId = isset($_GET['event_id']) ? (int) $_GET['event_id'] : 0;
$seats   = isset($_GET['seats']) ? (int) $_GET['seats'] : 0;


// Basic validation
if ($eventId <= 0 || $seats <= 0) {
    header("Location: events.php");
    exit();
}


// Fetch event
$stmt = $conn->prepare("
    SELECT
        events.id,
        events.title,
        events.description,
        events.venue,
        events.event_date,
        events.event_time,
        events.price,
        events.available_seats,
        events.image,
        events.status,
        categories.category_name
    FROM events
    INNER JOIN categories
        ON events.category_id = categories.id
    WHERE events.id = ?
    LIMIT 1
");

$stmt->bind_param("i", $eventId);
$stmt->execute();

$result = $stmt->get_result();


// Event not found
if ($result->num_rows !== 1) {
    header("Location: events.php");
    exit();
}

$event = $result->fetch_assoc();


// Check event status
if ($event['status'] !== 'Upcoming') {
    header("Location: event-details.php?id=" . $eventId);
    exit();
}


// Check available seats
if ($seats > $event['available_seats']) {
    header("Location: event-details.php?id=" . $eventId);
    exit();
}


// Calculate total
$totalAmount = $event['price'] * $seats;


require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>


<div class="container py-5">

    <div class="mb-4">

        <a
            href="event-details.php?id=<?= $event['id']; ?>"
            class="btn btn-outline-light"
        >
            ← Back to Event
        </a>

    </div>


    <div class="row g-4">


        <!-- Event Details -->

        <div class="col-lg-7">

            <div class="glass rounded-4 p-4">

                <h2 class="text-white fw-bold mb-4">
                    Booking Details 🎫
                </h2>


                <div class="row">


                    <?php if (!empty($event['image'])): ?>

                        <div class="col-md-5 mb-3">

                            <img
                                src="../assets/uploads/<?= htmlspecialchars($event['image']); ?>"
                                class="img-fluid rounded-4"
                                style="width:100%; height:250px; object-fit:cover;"
                                alt="<?= htmlspecialchars($event['title']); ?>"
                            >

                        </div>

                    <?php endif; ?>


                    <div class="col-md-7">

                        <span class="badge bg-info text-dark mb-2">

                            <?= htmlspecialchars($event['category_name']); ?>

                        </span>


                        <h3 class="text-white fw-bold">

                            <?= htmlspecialchars($event['title']); ?>

                        </h3>


                        <p class="text-secondary mb-3">

                            📍 <?= htmlspecialchars($event['venue']); ?>

                        </p>


                        <p class="text-secondary mb-3">

                            📅 <?= htmlspecialchars($event['event_date']); ?>

                        </p>


                        <p class="text-secondary mb-3">

                            ⏰ <?= htmlspecialchars($event['event_time']); ?>

                        </p>


                        <p class="text-white mb-0">

                            💰 Price per Seat:

                            <strong class="text-success">

                                ₹<?= number_format($event['price'], 2); ?>

                            </strong>

                        </p>

                    </div>

                </div>

            </div>

        </div>


        <!-- Booking Summary -->

        <div class="col-lg-5">

            <div class="glass rounded-4 p-4">

                <h4 class="text-white fw-bold mb-4">

                    Booking Summary

                </h4>


                <div class="d-flex justify-content-between mb-3">

                    <span class="text-secondary">
                        Number of Seats
                    </span>

                    <strong class="text-white">
                        <?= $seats; ?>
                    </strong>

                </div>


                <div class="d-flex justify-content-between mb-3">

                    <span class="text-secondary">
                        Price per Seat
                    </span>

                    <strong class="text-white">
                        ₹<?= number_format($event['price'], 2); ?>
                    </strong>

                </div>


                <hr class="border-secondary">


                <div class="d-flex justify-content-between mb-4">

                    <span class="text-white fw-bold">
                        Total Amount
                    </span>

                    <strong class="text-success fs-4">

                        ₹<?= number_format($totalAmount, 2); ?>

                    </strong>

                </div>


                <form
                    action="booking-process.php"
                    method="POST"
                >

                    <input
                        type="hidden"
                        name="event_id"
                        value="<?= $event['id']; ?>"
                    >


                    <input
                        type="hidden"
                        name="seats"
                        value="<?= $seats; ?>"
                    >


                    <button
                        type="submit"
                        class="btn btn-info w-100"
                    >

                        ✅ Confirm Booking

                    </button>

                </form>


                <a
                    href="event-details.php?id=<?= $event['id']; ?>"
                    class="btn btn-outline-light w-100 mt-3"
                >

                    Cancel

                </a>

            </div>

        </div>


    </div>

</div>


<?php

require_once '../includes/footer.php';

?>