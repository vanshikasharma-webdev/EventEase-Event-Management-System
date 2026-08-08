<?php

require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';
?>
<section class="hero-section d-flex align-items-center">

    <div class="container">

        <div class="row align-items-center">

            <!-- Left Side -->

            <div class="col-lg-6">

                <span class="badge bg-info text-dark px-3 py-2 mb-3">
                    🎉 India's Smart Event Management Platform
                </span>

                <h1 class="display-3 fw-bold mb-4">

                    Plan Events

                    <span class="text-info">

                        Without Stress

                    </span>

                </h1>

                <p class="lead text-light opacity-75 mb-4">

                    EventEase helps you manage weddings,
                    corporate events, birthday parties,
                    seminars and concerts effortlessly.

                </p>

                <a href="/EventEase/pages/events.php"
                    class="btn btn-info btn-lg rounded-pill px-4 me-3">

                    Explore Events

                </a>

                <a href="/EventEase/auth/register.php"
                    class="btn btn-outline-light btn-lg rounded-pill px-4">

                    Get Started

                </a>

            </div>

            <!-- Right Side -->

            <div class="col-lg-6 mt-5 mt-lg-0">

                <div class="glass p-4">

                    <div class="row g-3">

                        <div class="col-6">

                            <div class="stat-card">

                                <h2>250+</h2>

                                <p>Events Managed</p>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="stat-card">

                                <h2>5000+</h2>

                                <p>Bookings</p>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="stat-card">

                                <h2>120+</h2>

                                <p>Happy Clients</p>

                            </div>

                        </div>

                        <div class="col-6">

                            <div class="stat-card">

                                <h2>4.9★</h2>

                                <p>Average Rating</p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>
<!-- ==========================
Upcoming Events
========================== -->

<section>

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-4">

<h2 class="fw-bold">

Upcoming Events

</h2>

<a href="pages/events.php"
class="btn btn-outline-info">

View All

</a>

</div>

<div class="row">

<?php

$query = "SELECT *
          FROM events
          WHERE status='Upcoming'
          ORDER BY event_date ASC
          LIMIT 6";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("SQL Error: " . mysqli_error($conn));
}

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="glass h-100 p-3">

<img

src="assets/uploads/<?php echo $row['image'];?>"

class="img-fluid rounded mb-3"

alt="Event Image">

<h4>

<?php echo htmlspecialchars($row['title']); ?>

</h4>

<p class="text-secondary">

<?php

echo htmlspecialchars(substr($row['description'],0,100));

?>

...

</p>

<div class="mb-3">

<strong>Date:</strong>

<?php echo $row['event_date']; ?>

</div>

<div class="mb-3">

<strong>Venue:</strong>

<?php echo htmlspecialchars($row['venue']); ?>

</div>

<div class="d-flex
justify-content-between
align-items-center">

<h5 class="text-info">

₹<?php echo $row['price']; ?>

</h5>

<a href="pages/event-details.php?id=<?php echo $row['id']; ?>"
   class="btn btn-info">
    View Details
</a>

</div>

</div>

</div>

<?php

}

}else{

?>

<div class="col-12">

<div class="glass p-5 text-center">

<h3>

No Upcoming Events

</h3>

<p>

Please check back later.

</p>

</div>

</div>

<?php

}

?>

</div>

</div>

</section>

<?php
require_once 'includes/footer.php';
?>