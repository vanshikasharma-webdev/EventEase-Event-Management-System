<?php

/*
|--------------------------------------------------------------------------
| Prevent Browser from Caching Protected Page
|--------------------------------------------------------------------------
*/

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");


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
| User Authentication & Authorization
|--------------------------------------------------------------------------
*/

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['user_role']) ||
    $_SESSION['user_role'] !== 'User'
) {

    header("Location: ../auth/login.php");
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
| Active Bookings for Logged-in User
|--------------------------------------------------------------------------
|
| Cancelled bookings are NOT included in the count.
|
*/

$userId = (int) $_SESSION['user_id'];


$bookingQuery = mysqli_prepare(
    $conn,
    "
    SELECT COUNT(*) AS total
    FROM bookings
    WHERE user_id = ?
    AND booking_status != 'Cancelled'
    "
);


$totalBookings = 0;


if ($bookingQuery) {

    mysqli_stmt_bind_param(
        $bookingQuery,
        "i",
        $userId
    );


    mysqli_stmt_execute($bookingQuery);


    $bookingResult = mysqli_stmt_get_result($bookingQuery);


    if ($bookingResult) {

        $bookingData = mysqli_fetch_assoc($bookingResult);

        $totalBookings = (int) ($bookingData['total'] ?? 0);

    }


    mysqli_stmt_close($bookingQuery);

}

?>


<!--
|--------------------------------------------------------------------------
| Prevent Back/Forward Cache Issue
|--------------------------------------------------------------------------
-->

<script>

window.addEventListener('pageshow', function (event) {

    const navigationEntry =
        performance.getEntriesByType('navigation')[0];

    if (
        event.persisted ||
        (
            navigationEntry &&
            navigationEntry.type === 'back_forward'
        )
    ) {

        window.location.reload();

    }

});

</script>


<div class="container py-5">


    <!-- Welcome Section -->

    <div class="glass rounded-4 p-5 mb-4">

        <h2 class="text-white fw-bold">

            Welcome,

            <?= htmlspecialchars(
                $_SESSION['user_name'] ?? 'User',
                ENT_QUOTES,
                'UTF-8'
            ); ?>

            👋

        </h2>


        <p class="text-light">

            Logged in as

            <strong>

                <?= htmlspecialchars(
                    $_SESSION['user_role'] ?? 'User',
                    ENT_QUOTES,
                    'UTF-8'
                ); ?>

            </strong>

        </p>

    </div>


    <!-- Dashboard Statistics -->

    <div class="row g-4">


        <!-- My Bookings -->

        <div class="col-md-4">

            <div class="card bg-dark text-white border-0 shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        🎫 My Bookings
                    </h5>

                    <h2 class="text-info">

                        <?= $totalBookings; ?>

                    </h2>

                </div>

            </div>

        </div>


        <!-- Events -->

        <div class="col-md-4">

            <div class="card bg-dark text-white border-0 shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        🎉 Events
                    </h5>

                    <h2 class="text-info">

                        <?= $totalEvents; ?>

                    </h2>

                </div>

            </div>

        </div>


        <!-- Profile -->

        <div class="col-md-4">

            <div class="card bg-dark text-white border-0 shadow h-100">

                <div class="card-body text-center">

                    <h5>
                        👤 Profile
                    </h5>

                    <p class="mb-0">

                        <?= htmlspecialchars(
                            $_SESSION['user_email'] ?? '',
                            ENT_QUOTES,
                            'UTF-8'
                        ); ?>

                    </p>

                </div>

            </div>

        </div>

    </div>


    <!-- Quick Actions -->

    <div class="glass rounded-4 p-4 mt-5">

        <h4 class="text-white mb-4">
            Quick Actions
        </h4>


        <a
            href="/EventEase/pages/events.php"
            class="btn btn-info me-2 mb-2"
        >

            Browse Events

        </a>


        <a
            href="my-bookings.php"
            class="btn btn-outline-light me-2 mb-2"
        >

            My Bookings

        </a>


        <a
            href="profile.php"
            class="btn btn-outline-info me-2 mb-2"
        >

            My Profile

        </a>


        <a
            href="edit-profile.php"
            class="btn btn-outline-warning me-2 mb-2"
        >

            Edit Profile

        </a>


        <a
            href="change-password.php"
            class="btn btn-outline-danger mb-2"
        >

            Change Password

        </a>

    </div>

</div>


<?php

require_once '../includes/footer.php';

?>