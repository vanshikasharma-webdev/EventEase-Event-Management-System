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



/*
|--------------------------------------------------------------------------
| Get Event ID
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id'])) {

    header("Location: manage-events.php");
    exit();

}


$eventId = $_GET['id'];



/*
|--------------------------------------------------------------------------
| Fetch Event
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT *
    FROM events
    WHERE id = ?
");

$stmt->bind_param("i", $eventId);

$stmt->execute();

$result = $stmt->get_result();


if ($result->num_rows === 0) {

    header("Location: manage-events.php");
    exit();

}


$event = $result->fetch_assoc();



/*
|--------------------------------------------------------------------------
| Fetch Categories
|--------------------------------------------------------------------------
*/

$categoryQuery = $conn->prepare("
    SELECT id, category_name
    FROM categories
    WHERE status='Active'
");

$categoryQuery->execute();

$categories = $categoryQuery->get_result();



require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>


<div class="container py-5">


<div class="row justify-content-center">


<div class="col-lg-8">


<div class="glass rounded-4 p-5">


<h2 class="text-white fw-bold mb-4">
    Edit Event
</h2>



<form action="edit-event-process.php"
      method="POST"
      enctype="multipart/form-data">


<input type="hidden"
       name="id"
       value="<?= $event['id']; ?>">



<div class="mb-3">

<label class="form-label text-white">
Title
</label>

<input
type="text"
name="title"
class="form-control"
value="<?= htmlspecialchars($event['title']); ?>"
required>

</div>



<div class="mb-3">

<label class="form-label text-white">
Category
</label>


<select name="category_id"
class="form-select"
required>


<?php while($category = $categories->fetch_assoc()): ?>


<option 
value="<?= $category['id']; ?>"
<?= ($category['id'] == $event['category_id']) ? 'selected' : ''; ?>
>

<?= htmlspecialchars($category['category_name']); ?>

</option>


<?php endwhile; ?>


</select>

</div>




<div class="mb-3">

<label class="form-label text-white">
Description
</label>

<textarea
name="description"
class="form-control"
rows="5"
required><?= htmlspecialchars($event['description']); ?></textarea>

</div>




<div class="mb-3">

<label class="form-label text-white">
Venue
</label>

<input
type="text"
name="venue"
class="form-control"
value="<?= htmlspecialchars($event['venue']); ?>"
required>

</div>




<div class="row">


<div class="col-md-6 mb-3">

<label class="form-label text-white">
Event Date
</label>

<input
type="date"
name="event_date"
class="form-control"
value="<?= $event['event_date']; ?>"
required>

</div>




<div class="col-md-6 mb-3">

<label class="form-label text-white">
Event Time
</label>

<input
type="time"
name="event_time"
class="form-control"
value="<?= $event['event_time']; ?>"
required>

</div>


</div>





<div class="row">


<div class="col-md-6 mb-3">

<label class="form-label text-white">
Price
</label>

<input
type="number"
name="price"
class="form-control"
value="<?= $event['price']; ?>"
required>

</div>




<div class="col-md-6 mb-3">

<label class="form-label text-white">
Seats
</label>

<input
type="number"
name="available_seats"
class="form-control"
value="<?= $event['available_seats']; ?>"
required>

</div>


</div>





<div class="mb-3">

<label class="form-label text-white">
Current Image
</label>

<br>

<img
src="../assets/uploads/<?= htmlspecialchars($event['image']); ?>"
width="150"
class="rounded mb-3">


<input
type="file"
name="image"
class="form-control">

</div>





<div class="mb-4">

<label class="form-label text-white">
Status
</label>


<select name="status"
class="form-select">


<option value="Upcoming"
<?= $event['status']=='Upcoming'?'selected':''; ?>>
Upcoming
</option>


<option value="Completed"
<?= $event['status']=='Completed'?'selected':''; ?>>
Completed
</option>


<option value="Cancelled"
<?= $event['status']=='Cancelled'?'selected':''; ?>>
Cancelled
</option>


</select>

</div>





<button class="btn btn-info w-100">

Update Event

</button>



</form>


</div>


</div>


</div>


</div>


<?php

require_once '../includes/footer.php';

?>