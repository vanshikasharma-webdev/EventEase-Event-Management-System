<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';


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


/*
|--------------------------------------------------------------------------
| Fetch Active Categories
|--------------------------------------------------------------------------
*/

$categoryQuery = $conn->prepare("
    SELECT
        id,
        category_name
    FROM categories
    WHERE status = 'Active'
    ORDER BY category_name ASC
");

$categoryQuery->execute();

$categories = $categoryQuery->get_result();


require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="text-white fw-bold">
            Add New Event
        </h2>

        <a href="dashboard.php"
           class="btn btn-outline-light">

            ← Dashboard

        </a>

    </div>


    <?php if (isset($_SESSION['event_error'])): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($_SESSION['event_error']); ?>

        </div>

        <?php unset($_SESSION['event_error']); ?>

    <?php endif; ?>


    <div class="glass rounded-4 p-5">


        <form
            action="add-event-process.php"
            method="POST"
            enctype="multipart/form-data"
        >


            <!-- Category -->

            <div class="mb-3">

                <label class="form-label text-white">
                    Event Category
                </label>

                <select
                    name="category_id"
                    class="form-select"
                    required
                >

                    <option value="">
                        Select Category
                    </option>


                    <?php while ($category = $categories->fetch_assoc()): ?>

                        <option value="<?= $category['id']; ?>">

                            <?= htmlspecialchars(
                                $category['category_name']
                            ); ?>

                        </option>

                    <?php endwhile; ?>

                </select>

            </div>


            <!-- Title -->

            <div class="mb-3">

                <label class="form-label text-white">
                    Event Title
                </label>

                <input
                    type="text"
                    name="title"
                    class="form-control"
                    placeholder="Enter event title"
                    required
                >

            </div>


            <!-- Description -->

            <div class="mb-3">

                <label class="form-label text-white">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="5"
                    placeholder="Enter event description"
                    required
                ></textarea>

            </div>


            <!-- Venue -->

            <div class="mb-3">

                <label class="form-label text-white">
                    Venue
                </label>

                <input
                    type="text"
                    name="venue"
                    class="form-control"
                    placeholder="Enter event venue"
                    required
                >

            </div>


            <div class="row">


                <!-- Date -->

                <div class="col-md-6 mb-3">

                    <label class="form-label text-white">
                        Event Date
                    </label>

                    <input
                        type="date"
                        name="event_date"
                        class="form-control"
                        required
                    >

                </div>


                <!-- Time -->

                <div class="col-md-6 mb-3">

                    <label class="form-label text-white">
                        Event Time
                    </label>

                    <input
                        type="time"
                        name="event_time"
                        class="form-control"
                        required
                    >

                </div>


            </div>


            <div class="row">


                <!-- Price -->

                <div class="col-md-6 mb-3">

                    <label class="form-label text-white">
                        Price
                    </label>

                    <input
                        type="number"
                        name="price"
                        class="form-control"
                        min="0"
                        step="0.01"
                        placeholder="Enter price"
                        required
                    >

                </div>


                <!-- Seats -->

                <div class="col-md-6 mb-3">

                    <label class="form-label text-white">
                        Available Seats
                    </label>

                    <input
                        type="number"
                        name="available_seats"
                        class="form-control"
                        min="1"
                        placeholder="Enter available seats"
                        required
                    >

                </div>


            </div>


            <!-- Image -->

            <div class="mb-3">

                <label class="form-label text-white">
                    Event Image
                </label>

                <input
                    type="file"
                    name="image"
                    class="form-control"
                    accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                    required
                >

            </div>


            <!-- Status -->

            <div class="mb-4">

                <label class="form-label text-white">
                    Event Status
                </label>

                <select
                    name="status"
                    class="form-select"
                    required
                >

                    <option value="Upcoming">
                        Upcoming
                    </option>

                    <option value="Completed">
                        Completed
                    </option>

                    <option value="Cancelled">
                        Cancelled
                    </option>

                </select>

            </div>


            <button
                type="submit"
                class="btn btn-info w-100"
            >

                ➕ Add Event

            </button>


        </form>


    </div>

</div>


<?php

require_once '../includes/footer.php';

?>