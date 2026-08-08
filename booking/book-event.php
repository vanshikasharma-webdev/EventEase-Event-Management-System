<?php


require_once '../config/database.php';
require_once '../includes/auth-check.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$user_id = $_SESSION['user_id'];

$event_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if($event_id == 0){
    die("Invalid Event");
}

$query = "SELECT * FROM events WHERE id=$event_id LIMIT 1";

$result = mysqli_query($conn,$query);

if(mysqli_num_rows($result)==0){
    die("Event not found");
}

$event = mysqli_fetch_assoc($result);

$total_amount = $event['price'];

if(isset($_POST['book_now'])){

    $query = "INSERT INTO bookings
    (user_id,event_id,total_amount)
    VALUES
    ('$user_id','$event_id','$total_amount')";

    if(mysqli_query($conn,$query)){

        $_SESSION['booking_success']="Booking Successful";

        header("Location: my-bookings.php");
        exit();

    }else{

        echo mysqli_error($conn);

    }

}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>
<div class="container py-5">

<div class="glass p-5 rounded-4">

<h2 class="text-white mb-4">

Confirm Booking

</h2>

<h4>

<?php 
echo htmlspecialchars($event['title']); 
?>

</h4>

<p>

Venue:
<?php echo htmlspecialchars($event['venue']); ?>

</p>

<p>

Date:
<?php echo $event['event_date']; ?>

</p>

<h3 class="text-info">

₹<?php echo $event['price']; ?>

</h3>

<form action="book-event-process.php" method="POST">

<input
type="hidden"
name="event_id"
value="<?= $event['id']; ?>">

<button
class="btn btn-info"
name="book_now">

Confirm Booking

</button>

</form>

</div>

</div>

<?php
require_once '../includes/footer.php';
?>