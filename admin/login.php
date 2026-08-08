<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    isset($_SESSION['admin_id']) &&
    isset($_SESSION['admin_role']) &&
    $_SESSION['admin_role'] === 'Admin'
) {
    header("Location: dashboard.php");
    exit();
}
require_once '../includes/header.php';
?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-5">

            <div class="glass p-5 rounded-4">

                <h2 class="text-center text-white mb-4">
                    Admin Login
                </h2>

                <?php
                if (isset($_SESSION['login_error'])) {
                    echo '<div class="alert alert-danger">'
                        . htmlspecialchars($_SESSION['login_error']) .
                        '</div>';

                    unset($_SESSION['login_error']);
                }
                ?>

                <form action="login_process.php" method="POST">

                    <div class="mb-3">

                        <label class="form-label text-white">

                            Email

                        </label>

                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-4">

                        <label class="form-label text-white">

                            Password

                        </label>

                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            required>

                    </div>

                    <button
                        class="btn btn-info w-100">

                        Login

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php
require_once '../includes/footer.php';
?>