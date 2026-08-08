<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';


/*
|--------------------------------------------------------------------------
| User Authentication
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Get Event ID
|--------------------------------------------------------------------------
*/

$eventId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($eventId <= 0) {
    header("Location: events.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Fetch Event
|--------------------------------------------------------------------------
*/

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
    AND categories.status = 'Active'
    LIMIT 1
");

$stmt->bind_param("i", $eventId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: events.php");
    exit();
}

$event = $result->fetch_assoc();


require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>

<div class="container py-5">

    <!-- Back Button -->

    <div class="mb-4">

        <a
            href="events.php"
            class="btn btn-outline-light"
        >
            ← Back to Events
        </a>

    </div>


    <div class="row g-5">

        <!-- LEFT: EVENT IMAGE -->

        <div class="col-lg-6">

            <div class="card bg-dark border-0 shadow overflow-hidden">

                <?php if (!empty($event['image'])): ?>

                    <img
                        src="../assets/uploads/<?= htmlspecialchars($event['image']); ?>"
                        class="img-fluid"
                        style="width:100%; height:450px; object-fit:cover;"
                        alt="<?= htmlspecialchars($event['title']); ?>"
                    >

                <?php else: ?>

                    <div
                        class="d-flex justify-content-center align-items-center text-secondary"
                        style="height:450px;"
                    >
                        No Image Available
                    </div>

                <?php endif; ?>

            </div>

        </div>


        <!-- RIGHT: EVENT INFORMATION -->

        <div class="col-lg-6">

            <span class="badge bg-info text-dark mb-3">

                <?= htmlspecialchars($event['category_name']); ?>

            </span>


            <h1 class="text-white fw-bold mb-3">

                <?= htmlspecialchars($event['title']); ?>

            </h1>


            <p class="text-light fs-5 mb-4">

                <?= nl2br(
                    htmlspecialchars($event['description'])
                ); ?>

            </p>


            <!-- EVENT INFORMATION CARD -->

            <div class="glass rounded-4 p-4 mb-4">

                <div class="mb-3">

                    <span class="text-secondary">
                        📍 Venue
                    </span>

                    <div class="text-white fw-semibold">

                        <?= htmlspecialchars($event['venue']); ?>

                    </div>

                </div>


                <div class="mb-3">

                    <span class="text-secondary">
                        📅 Date
                    </span>

                    <div class="text-white fw-semibold">

                        <?= htmlspecialchars($event['event_date']); ?>

                    </div>

                </div>


                <div class="mb-3">

                    <span class="text-secondary">
                        ⏰ Time
                    </span>

                    <div class="text-white fw-semibold">

                        <?= htmlspecialchars($event['event_time']); ?>

                    </div>

                </div>


                <div class="mb-3">

                    <span class="text-secondary">
                        💺 Available Seats
                    </span>

                    <div class="text-white fw-semibold">

                        <?= $event['available_seats']; ?>

                    </div>

                </div>


                <div>

                    <span class="text-secondary">
                        💰 Price per Seat
                    </span>

                    <div class="text-success fw-bold fs-3">

                        ₹<?= number_format(
                            $event['price'],
                            2
                        ); ?>

                    </div>

                </div>

            </div>


            <!-- BOOKING SECTION -->

            <?php if (
                $event['status'] === 'Upcoming'
                && $event['available_seats'] > 0
            ): ?>

                <div class="glass rounded-4 p-4 mb-4">

                    <h5 class="text-white fw-bold mb-3">
                        Book Your Seats 🎫
                    </h5>


                    <form
                        action="booking.php"
                        method="GET"
                    >

                        <input
                            type="hidden"
                            name="event_id"
                            value="<?= $event['id']; ?>"
                        >


                        <div class="mb-3">

                            <label class="form-label text-white">
                                Number of Seats
                            </label>

                            <input
                                type="number"
                                name="seats"
                                class="form-control"
                                min="1"
                                max="<?= $event['available_seats']; ?>"
                                value="1"
                                required
                            >

                        </div>


                        <button
                            type="submit"
                            class="btn btn-info w-100"
                        >
                            🎫 Book Now
                        </button>

                    </form>

                </div>


            <?php elseif ($event['available_seats'] <= 0): ?>

                <div class="alert alert-danger text-center">

                    This event is sold out.

                </div>


            <?php else: ?>

                <div class="alert alert-secondary text-center">

                    Booking is currently closed.

                </div>

            <?php endif; ?>

        </div>

    </div>

</div>


<?php

require_once '../includes/footer.php';

?>