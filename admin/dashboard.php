<?php

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
| Prevent Browser Caching
|--------------------------------------------------------------------------
*/

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");


/*
|--------------------------------------------------------------------------
| Admin Authentication & Authorization
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
| Database
|--------------------------------------------------------------------------
*/

require_once '../config/database.php';


/*
|--------------------------------------------------------------------------
| Header & Navbar
|--------------------------------------------------------------------------
*/

require_once '../includes/header.php';
require_once '../includes/navbar.php';


/*
|--------------------------------------------------------------------------
| Dashboard Statistics
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| Total Users
|--------------------------------------------------------------------------
*/

$userQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM users"
);

$totalUsers = 0;

if ($userQuery) {

    $userData = mysqli_fetch_assoc($userQuery);

    $totalUsers = (int) ($userData['total'] ?? 0);

}


/*
|--------------------------------------------------------------------------
| Total Events
|--------------------------------------------------------------------------
*/

$eventQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM events"
);

$totalEvents = 0;

if ($eventQuery) {

    $eventData = mysqli_fetch_assoc($eventQuery);

    $totalEvents = (int) ($eventData['total'] ?? 0);

}


/*
|--------------------------------------------------------------------------
| Total Bookings
|--------------------------------------------------------------------------
*/

$bookingQuery = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM bookings"
);

$totalBookings = 0;

if ($bookingQuery) {

    $bookingData = mysqli_fetch_assoc($bookingQuery);

    $totalBookings = (int) ($bookingData['total'] ?? 0);

}


/*
|--------------------------------------------------------------------------
| Total Revenue
|--------------------------------------------------------------------------
|
| Only confirmed bookings are included.
|
*/

$revenueQuery = mysqli_query(
    $conn,
    "
    SELECT
        COALESCE(SUM(total_amount), 0) AS revenue
    FROM bookings
    WHERE booking_status = 'Confirmed'
    "
);

$revenue = 0;

if ($revenueQuery) {

    $revenueData = mysqli_fetch_assoc($revenueQuery);

    $revenue = (float) ($revenueData['revenue'] ?? 0);

}

?>


<!--
|--------------------------------------------------------------------------
| Prevent Back-Button BFCache Issue
|--------------------------------------------------------------------------
|
| If this protected page is restored from browser history/cache,
| force a fresh request so PHP authentication runs again.
|
-->

<script>

window.addEventListener('pageshow', function (event) {

    if (event.persisted) {

        window.location.reload();

    }

});

</script>


<div class="container py-5">


    <!-- Welcome Section -->

    <div class="glass rounded-4 p-5 mb-5">

        <h2 class="text-white fw-bold">
            Welcome Admin 👋
        </h2>

        <p class="text-light mb-0">

            Logged in as:

            <strong>
                <?= htmlspecialchars(
                    $_SESSION['admin_name'] ?? 'Admin',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>
            </strong>

        </p>

    </div>


    <!-- Dashboard Statistics -->

    <div class="row g-4">


        <!-- Total Users -->

        <div class="col-md-3">

            <div class="card bg-dark text-white border-0 shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        👥 Total Users
                    </h5>

                    <h2 class="text-info">
                        <?= $totalUsers; ?>
                    </h2>

                </div>

            </div>

        </div>


        <!-- Total Events -->

        <div class="col-md-3">

            <div class="card bg-dark text-white border-0 shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        🎉 Total Events
                    </h5>

                    <h2 class="text-info">
                        <?= $totalEvents; ?>
                    </h2>

                </div>

            </div>

        </div>


        <!-- Total Bookings -->

        <div class="col-md-3">

            <div class="card bg-dark text-white border-0 shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        🎫 Total Bookings
                    </h5>

                    <h2 class="text-info">
                        <?= $totalBookings; ?>
                    </h2>

                </div>

            </div>

        </div>


        <!-- Total Revenue -->

        <div class="col-md-3">

            <div class="card bg-dark text-white border-0 shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        💰 Total Revenue
                    </h5>

                    <h2 class="text-success">
                        ₹<?= number_format($revenue, 2); ?>
                    </h2>

                </div>

            </div>

        </div>

    </div>


    <!-- Admin Panel -->

    <div class="glass rounded-4 p-4 mt-5">

        <h4 class="text-white mb-4">
            Admin Panel
        </h4>


        <a
            href="add-event.php"
            class="btn btn-info me-2 mb-2"
        >

            ➕ Add Event

        </a>


        <a
            href="manage-events.php"
            class="btn btn-primary me-2 mb-2"
        >

            📅 Manage Events

        </a>


        <a
            href="manage-categories.php"
            class="btn btn-secondary me-2 mb-2"
        >

            🏷️ Manage Categories

        </a>


        <a
            href="manage-bookings.php"
            class="btn btn-warning me-2 mb-2"
        >

            🎫 Manage Bookings

        </a>


        <a
            href="manage-users.php"
            class="btn btn-success mb-2"
        >

            👥 Manage Users

        </a>

    </div>

</div>


<?php

require_once '../includes/footer.php';

?>