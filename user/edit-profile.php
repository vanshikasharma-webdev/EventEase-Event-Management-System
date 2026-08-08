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

$userId = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT
        full_name,
        email,
        phone,
        profile_image
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $userId);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="glass rounded-4 p-5">

                <h2 class="text-white fw-bold mb-4">
                    Edit Profile
                </h2>

                <?php if (isset($_SESSION['profile_error'])): ?>

                    <div class="alert alert-danger">
                        <?= htmlspecialchars($_SESSION['profile_error']); ?>
                    </div>

                    <?php unset($_SESSION['profile_error']); ?>

                <?php endif; ?>


                <form
                    action="edit-profile-process.php"
                    method="POST"
                    enctype="multipart/form-data"
                >

                    <div class="mb-3">

                        <label class="form-label text-white">
                            Full Name
                        </label>

                        <input
                            type="text"
                            name="full_name"
                            class="form-control"
                            value="<?= htmlspecialchars($user['full_name']); ?>"
                            required
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label text-white">
                            Email
                        </label>

                        <input
                            type="email"
                            class="form-control"
                            value="<?= htmlspecialchars($user['email']); ?>"
                            disabled
                        >

                    </div>


                    <div class="mb-3">

                        <label class="form-label text-white">
                            Phone
                        </label>

                        <input
                            type="text"
                            name="phone"
                            class="form-control"
                            value="<?= htmlspecialchars($user['phone']); ?>"
                            required
                        >

                    </div>


                    <div class="mb-4">

                        <label class="form-label text-white">
                            Profile Image
                        </label>

                        <div class="mb-3">

                            <img
                                src="../assets/uploads/<?= htmlspecialchars($user['profile_image'] ?: 'default.png'); ?>"
                                width="120"
                                height="120"
                                class="rounded-circle"
                                style="object-fit: cover;"
                                alt="Profile Image"
                            >

                        </div>


                        <input
                            type="file"
                            name="profile_image"
                            class="form-control"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <small class="text-light">
                            Select JPG, PNG or WEBP image.
                        </small>

                    </div>


                    <button
                        type="submit"
                        class="btn btn-info w-100"
                    >
                        Update Profile
                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php

require_once '../includes/footer.php';

?>