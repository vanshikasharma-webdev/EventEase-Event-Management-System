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
| Fetch Events With Category
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        events.id,
        events.title,
        events.description,
        events.venue,
        events.event_date,
        events.event_time,
        events.price,
        events.available_seats,
        events.image,
        events.status,
        categories.category_name
    FROM events
    LEFT JOIN categories
        ON events.category_id = categories.id
    ORDER BY events.event_date ASC
");

$stmt->execute();

$events = $stmt->get_result();


require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>


<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="text-white fw-bold">
            Manage Events
        </h2>

        <div>

            <a href="add-event.php"
               class="btn btn-info me-2">

                ➕ Add Event

            </a>

            <a href="dashboard.php"
               class="btn btn-outline-light">

                ← Dashboard

            </a>

        </div>

    </div>


    <?php if (isset($_SESSION['event_success'])): ?>

        <div class="alert alert-success">

            <?= htmlspecialchars($_SESSION['event_success']); ?>

        </div>

        <?php unset($_SESSION['event_success']); ?>

    <?php endif; ?>


    <?php if (isset($_SESSION['event_error'])): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($_SESSION['event_error']); ?>

        </div>

        <?php unset($_SESSION['event_error']); ?>

    <?php endif; ?>


    <div class="table-responsive">

        <table class="table table-dark table-bordered align-middle">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Image</th>

                    <th>Title</th>

                    <th>Category</th>

                    <th>Venue</th>

                    <th>Date</th>

                    <th>Price</th>

                    <th>Seats</th>

                    <th>Status</th>

                    <th>Actions</th>

                </tr>

            </thead>


            <tbody>


                <?php if ($events->num_rows > 0): ?>


                    <?php while ($event = $events->fetch_assoc()): ?>

                        <tr>


                            <td>
                                <?= $event['id']; ?>
                            </td>


                            <td>

                                <?php if (!empty($event['image'])): ?>

                                    <img
                                        src="../assets/uploads/<?= htmlspecialchars($event['image']); ?>"
                                        width="80"
                                        height="60"
                                        style="object-fit: cover;"
                                        class="rounded"
                                        alt="Event Image"
                                    >

                                <?php else: ?>

                                    <span class="text-muted">
                                        No Image
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $event['title']
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $event['category_name'] ?? 'Uncategorized'
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $event['venue']
                                ); ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $event['event_date']
                                ); ?>

                                <br>

                                <small class="text-secondary">

                                    <?= htmlspecialchars(
                                        $event['event_time']
                                    ); ?>

                                </small>

                            </td>


                            <td>

                                ₹<?= number_format(
                                    $event['price'],
                                    2
                                ); ?>

                            </td>


                            <td>

                                <?= $event['available_seats']; ?>

                            </td>


                            <td>

                                <?php if ($event['status'] === 'Upcoming'): ?>

                                    <span class="badge bg-success">
                                        Upcoming
                                    </span>

                                <?php elseif ($event['status'] === 'Completed'): ?>

                                    <span class="badge bg-secondary">
                                        Completed
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-danger">
                                        Cancelled
                                    </span>

                                <?php endif; ?>

                            </td>


                            <td>

                                <a
                                    href="edit-event.php?id=<?= $event['id']; ?>"
                                    class="btn btn-sm btn-warning mb-1"
                                >
                                    ✏️ Edit
                                </a>


                                <a
                                    href="delete-event.php?id=<?= $event['id']; ?>"
                                    class="btn btn-sm btn-danger mb-1"
                                    onclick="return confirm('Are you sure you want to delete this event?');"
                                >
                                    🗑️ Delete
                                </a>

                            </td>


                        </tr>

                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="10"
                            class="text-center py-4">

                            No events found.

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