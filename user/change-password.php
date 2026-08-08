<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

require_once '../config/database.php';

require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="glass rounded-4 p-5">

                <h2 class="text-white fw-bold mb-4">
                    Change Password
                </h2>

                <?php if (isset($_SESSION['password_error'])): ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['password_error']); ?>
                    </div>

                    <?php unset($_SESSION['password_error']); ?>

                <?php endif; ?>


                <?php if (isset($_SESSION['password_success'])): ?>

                    <div class="alert alert-success">
                        <?= htmlspecialchars($_SESSION['password_success']); ?>
                    </div>

                    <?php unset($_SESSION['password_success']); ?>

                <?php endif; ?>


                <form
                    action="change-password-process.php"
                    method="POST"
                >

                    <div class="mb-3">

                        <label class="form-label text-white">
                            Current Password
                        </label>

                        <input
                            type="password"
                            name="current_password"
                            class="form-control"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label text-white">
                            New Password
                        </label>

                        <input
                            type="password"
                            name="new_password"
                            class="form-control"
                            minlength="8"
                            required
                        >

                        <small class="text-light">
                            Minimum 8 characters.
                        </small>

                    </div>


                    <div class="mb-4">

                        <label class="form-label text-white">
                            Confirm New Password
                        </label>

                        <input
                            type="password"
                            name="confirm_password"
                            class="form-control"
                            minlength="8"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn btn-info w-100"
                    >
                        Change Password
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php

require_once '../includes/footer.php';

?>