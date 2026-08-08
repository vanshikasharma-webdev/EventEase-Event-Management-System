<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


/*
|--------------------------------------------------------------------------
| User Authentication
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {

    header("Location: ../auth/login.php");
    exit();

}


require_once '../config/database.php';


require_once '../includes/header.php';
require_once '../includes/navbar.php';



$userId = $_SESSION['user_id'];



/*
|--------------------------------------------------------------------------
| Fetch User Data
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT 
        id,
        full_name,
        email,
        phone,
        profile_image,
        role,
        created_at

    FROM users

    WHERE id = ?

");


$stmt->bind_param(
    "i",
    $userId
);


$stmt->execute();


$result = $stmt->get_result();


$user = $result->fetch_assoc();


?>


<div class="container py-5">


<div class="row justify-content-center">


<div class="col-md-6">


<div class="glass rounded-4 p-5 text-center">


<h2 class="text-white fw-bold mb-4">

My Profile

</h2>



<img
src="../assets/uploads/<?= htmlspecialchars($user['profile_image']); ?>"
width="120"
height="120"
class="rounded-circle mb-4"
style="object-fit:cover;"



>



<h3 class="text-white">

<?= htmlspecialchars($user['full_name']); ?>

</h3>



<p class="text-light">

📧 <?= htmlspecialchars($user['email']); ?>

</p>



<p class="text-light">

📱 <?= htmlspecialchars($user['phone']); ?>

</p>



<p class="text-light">

Role:

<strong>

<?= htmlspecialchars($user['role']); ?>

</strong>

</p>



<p class="text-light">

Joined:

<?= htmlspecialchars($user['created_at']); ?>

</p>




<a href="edit-profile.php"

class="btn btn-info mt-3">

✏️ Edit Profile

</a>



</div>


</div>


</div>


</div>



<?php

require_once '../includes/footer.php';

?>