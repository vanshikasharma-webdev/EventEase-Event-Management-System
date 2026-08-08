<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


/*
|--------------------------------------------------------------------------
| Start Session
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
| Fetch Upcoming Events
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
        categories.category_name
    FROM events
    INNER JOIN categories
        ON events.category_id = categories.id
    WHERE events.status = 'Upcoming'
    AND categories.status = 'Active'
    ORDER BY events.event_date ASC
");


if (!$stmt) {
    die("Failed to prepare events query.");
}


$stmt->execute();


$events = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| Header & Navbar
|--------------------------------------------------------------------------
*/

require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>

<div class="container py-5">

    <!-- Page Header -->

    <div class="d-flex justify-content-between align-items-center mb-5">

        <div>

            <h2 class="text-white fw-bold">
                Explore Events 🎉
            </h2>

            <p class="text-light mb-0">
                Discover upcoming events and book your seats.
            </p>

        </div>


        <?php if (isset($_SESSION['user_id'])): ?>

            <a
                href="../user/dashboard.php"
                class="btn btn-outline-light"
            >
                ← Dashboard
            </a>

        <?php else: ?>

            <a
                href="../auth/login.php"
                class="btn btn-outline-light"
            >
                Login to Book
            </a>

        <?php endif; ?>

    </div>


    <!-- Events -->

    <div class="row g-4">

        <?php if ($events->num_rows > 0): ?>

            <?php while ($event = $events->fetch_assoc()): ?>

                <div class="col-md-6 col-lg-4">

                    <div class="card bg-dark text-white border-0 shadow h-100">

                        <!-- Event Image -->

                        <?php if (!empty($event['image'])): ?>

                            <img
                                src="../assets/uploads/<?= htmlspecialchars($event['image'], ENT_QUOTES, 'UTF-8'); ?>"
                                class="card-img-top"
                                style="height:220px; object-fit:cover;"
                                alt="<?= htmlspecialchars($event['title'], ENT_QUOTES, 'UTF-8'); ?>"
                            >

                        <?php endif; ?>


                        <div class="card-body d-flex flex-column">

                            <!-- Category -->

                            <span class="badge bg-info text-dark align-self-start mb-2">

                                <?= htmlspecialchars(
                                    $event['category_name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </span>


                            <!-- Title -->

                            <h4 class="card-title">

                                <?= htmlspecialchars(
                                    $event['title'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </h4>


                            <!-- Description -->

                            <p class="text-secondary">

                                <?= htmlspecialchars(
                                    substr($event['description'], 0, 120),
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                                ...

                            </p>


                            <!-- Venue -->

                            <p class="mb-1">

                                📍

                                <?= htmlspecialchars(
                                    $event['venue'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </p>


                            <!-- Date -->

                            <p class="mb-1">

                                📅

                                <?= htmlspecialchars(
                                    $event['event_date'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </p>


                            <!-- Time -->

                            <p class="mb-1">

                                ⏰

                                <?= htmlspecialchars(
                                    $event['event_time'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ); ?>

                            </p>


                            <!-- Available Seats -->

                            <p class="mb-3">

                                💺

                                <?= (int) $event['available_seats']; ?>

                                seats available

                            </p>


                            <div class="mt-auto">


                                <!-- Price + Availability -->

                                <div class="d-flex justify-content-between align-items-center mb-3">

                                    <strong class="text-success fs-5">

                                        ₹<?= number_format(
                                            (float) $event['price'],
                                            2
                                        ); ?>

                                    </strong>


                                    <?php if ($event['available_seats'] > 0): ?>

                                        <span class="badge bg-success">

                                            Available

                                        </span>

                                    <?php else: ?>

                                        <span class="badge bg-danger">

                                            Sold Out

                                        </span>

                                    <?php endif; ?>

                                </div>


                                <!-- View Details -->

                                <?php if ($event['available_seats'] > 0): ?>

                                    <a
                                        href="event-details.php?id=<?= (int) $event['id']; ?>"
                                        class="btn btn-info w-100"
                                    >

                                        View Details

                                    </a>

                                <?php else: ?>

                                    <button
                                        class="btn btn-secondary w-100"
                                        disabled
                                    >

                                        Sold Out

                                    </button>

                                <?php endif; ?>


                            </div>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>


        <?php else: ?>

            <div class="col-12">

                <div class="alert alert-info text-center">

                    No upcoming events available right now.

                </div>

            </div>

        <?php endif; ?>

    </div>

</div>


<?php

$stmt->close();

require_once '../includes/footer.php';

?>