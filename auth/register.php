<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$old = $_SESSION['old'] ?? [];
unset($_SESSION['old']);

require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

$fullName = "";
$email = "";
$phone = "";

$errors = [];
$success = "";

?>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    // Validation yahan hogi...
}
?>
<section class="py-5">

<div class="container">

<div class="row justify-content-center">

<div class="col-lg-6">

<div class="glass p-5">

<h2 class="text-center mb-4">

Create Your Account

</h2>

<?php if(!empty($errors)): ?>

<div class="alert alert-danger">
    ...
</div>

<?php endif; ?>

<?php if($success): ?>

<div class="alert alert-success">
    ...
</div>

<?php endif; ?>

<?php

if(isset($_SESSION['success'])){

?>

<div class="alert alert-success">

<?= $_SESSION['success']; ?>

</div>

<?php

unset($_SESSION['success']);

}

?>

<?php

if(isset($_SESSION['errors'])){

?>

<div class="alert alert-danger">

<ul>

<?php

foreach($_SESSION['errors'] as $error){

echo "<li>$error</li>";

}

?>

</ul>

</div>

<?php

unset($_SESSION['errors']);

}

?>

<form action="register_process.php" method="POST">

<div class="mb-3">

<label class="form-label">

Full Name

</label>

<input
type="text"
name="full_name"
class="form-control"
value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"

</div>

<div class="mb-3">

    <label class="form-label">
        Email Address
    </label>

    <input
        type="email"
        name="email"
        class="form-control"
        placeholder="Enter your email"
        value="<?= htmlspecialchars($old['email'] ?? '') ?>"

</div>

<div class="mb-3">

    <label class="form-label">
        Phone Number
    </label>

    <input
        type="text"
        name="phone"
        class="form-control"
        placeholder="Enter your phone number"
       value="<?= htmlspecialchars($old['phone'] ?? '') ?>"

</div>

<div class="mb-3">

<label class="form-label">

Password

</label>

<input
type="password"
name="password"
class="form-control"
placeholder="Create password"
required>

</div>

<div class="mb-4">

<label class="form-label">

Confirm Password

</label>

<input
type="password"
name="confirm_password"
class="form-control"
placeholder="Confirm password"
required>

</div>

<button
type="submit"
class="btn btn-info w-100">

Create Account

</button>

<div class="text-center mt-4">

Already have an account?

<a href="login.php">

Login

</a>

</div>

</form>

</div>

</div>

</div>

</div>

</section>

<?php
require_once '../includes/footer.php';
?>