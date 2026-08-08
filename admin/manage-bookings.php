<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| Admin Authentication
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


require_once '../config/database.php';

require_once '../includes/header.php';
require_once '../includes/navbar.php';



/*
|--------------------------------------------------------------------------
| Fetch Bookings
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT

    bookings.id,
    bookings.booking_date,
    bookings.total_amount,
    bookings.booking_status,

    users.full_name,
    users.email,

    events.title,
    events.event_date,
    events.venue

    FROM bookings

    INNER JOIN users
    ON bookings.user_id = users.id

    INNER JOIN events
    ON bookings.event_id = events.id

    ORDER BY bookings.booking_date DESC

");


$stmt->execute();


$result = $stmt->get_result();


?>


<div class="container py-5">


<div class="d-flex justify-content-between align-items-center mb-4">


<h2 class="text-white fw-bold">
Manage Bookings
</h2>


<a href="dashboard.php"
class="btn btn-outline-light">

← Dashboard

</a>


</div>



<div class="table-responsive">


<table class="table table-dark table-bordered align-middle">


<thead>

<tr>

<th>ID</th>

<th>User</th>

<th>Event</th>

<th>Date</th>

<th>Amount</th>

<th>Status</th>

<th>Action</th>

</tr>


</thead>



<tbody>



<?php if($result->num_rows > 0): ?>



<?php while($booking = $result->fetch_assoc()): ?>


<tr>


<td>

<?= $booking['id']; ?>

</td>



<td>

<strong>
<?= htmlspecialchars($booking['full_name']); ?>
</strong>

<br>

<small>
<?= htmlspecialchars($booking['email']); ?>
</small>

</td>



<td>

<?= htmlspecialchars($booking['title']); ?>

<br>

<small>

<?= htmlspecialchars($booking['venue']); ?>

</small>

</td>



<td>

<?= $booking['booking_date']; ?>

</td>



<td>

₹<?= number_format($booking['total_amount'],2); ?>

</td>




<td>


<?php if($booking['booking_status']=="Confirmed"): ?>


<span class="badge bg-success">

Confirmed

</span>


<?php elseif($booking['booking_status']=="Cancelled"): ?>


<span class="badge bg-danger">

Cancelled

</span>


<?php else: ?>


<span class="badge bg-warning text-dark">

Pending

</span>


<?php endif; ?>


</td>




<td>


<a
href="update-booking-status.php?id=<?= $booking['id']; ?>&status=Confirmed"
class="btn btn-sm btn-success mb-1">

Confirm

</a>



<a
href="update-booking-status.php?id=<?= $booking['id']; ?>&status=Cancelled"
class="btn btn-sm btn-danger mb-1">

Cancel

</a>


</td>


</tr>



<?php endwhile; ?>



<?php else: ?>


<tr>

<td colspan="7"
class="text-center">

No Bookings Found

</td>

</tr>



<?php endif; ?>



</tbody>


</table>


</div>


</div>



<?php

require_once '../includes/footer.php';

?>