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


$message = $_SESSION['booking_success']
    ?? "Your booking has been confirmed.";

unset($_SESSION['booking_success']);


require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>


<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="glass rounded-4 p-5 text-center">

                <div
                    class="mb-4"
                    style="font-size:60px;"
                >
                    🎉
                </div>


                <h1 class="text-white fw-bold mb-3">

                    Booking Confirmed!

                </h1>


                <p class="text-light fs-5 mb-4">

                    <?= htmlspecialchars($message); ?>

                </p>


                <p class="text-secondary mb-4">

                    Thank you for booking with EventEase.

                </p>


                <a
                    href="events.php"
                    class="btn btn-info me-2"
                >

                    📅 Browse Events

                </a>


                <a
                    href="dashboard.php"
                    class="btn btn-outline-light"
                >

                    🏠 Dashboard

                </a>

            </div>

        </div>

    </div>

</div>


<?php

require_once '../includes/footer.php';

?>