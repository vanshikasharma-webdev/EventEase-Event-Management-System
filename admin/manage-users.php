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
| Fetch Users
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT 
        id,
        full_name,
        email,
        phone,
        role,
        status,
        created_at
    FROM users
    ORDER BY created_at DESC
");


$stmt->execute();


$result = $stmt->get_result();


?>


<div class="container py-5">


    <div class="d-flex justify-content-between align-items-center mb-4">


        <h2 class="text-white fw-bold">
            Manage Users
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

<th>Name</th>

<th>Email</th>

<th>Phone</th>

<th>Role</th>

<th>Status</th>

<th>Created At</th>

<th>Action</th>

</tr>

</thead>



<tbody>


<?php if($result->num_rows > 0): ?>


<?php while($user = $result->fetch_assoc()): ?>


<tr>


<td>
<?= $user['id']; ?>
</td>


<td>
<?= htmlspecialchars($user['full_name']); ?>
</td>


<td>
<?= htmlspecialchars($user['email']); ?>
</td>


<td>
<?= htmlspecialchars($user['phone']); ?>
</td>


<td>

<span class="badge bg-info text-dark">

<?= htmlspecialchars($user['role']); ?>

</span>

</td>



<td>


<?php if($user['status'] === 'Active'): ?>


<span class="badge bg-success">
Active
</span>


<?php else: ?>


<span class="badge bg-danger">
Inactive
</span>


<?php endif; ?>


</td>



<td>
<?= $user['created_at']; ?>
</td>



<td>


<a 
href="toggle-user-status.php?id=<?= $user['id']; ?>"
class="btn btn-sm btn-warning"
onclick="return confirm('Change user status?');">

Change Status

</a>


</td>



</tr>


<?php endwhile; ?>


<?php else: ?>


<tr>

<td colspan="8" class="text-center">

No Users Found

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