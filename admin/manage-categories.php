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
| Add Category
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $categoryName = trim($_POST['category_name'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($categoryName === '') {

        $_SESSION['category_error'] = "Category name is required.";

    } else {

        $stmt = $conn->prepare("
            SELECT id
            FROM categories
            WHERE category_name = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $categoryName);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            $_SESSION['category_error'] =
                "This category already exists.";

        } else {

            $stmt = $conn->prepare("
                INSERT INTO categories
                (category_name, description, status)
                VALUES (?, ?, 'Active')
            ");

            $stmt->bind_param(
                "ss",
                $categoryName,
                $description
            );

            if ($stmt->execute()) {

                $_SESSION['category_success'] =
                    "Category added successfully.";

            } else {

                $_SESSION['category_error'] =
                    "Unable to add category.";

            }
        }
    }

    header("Location: manage-categories.php");
    exit();
}


/*
|--------------------------------------------------------------------------
| Fetch Categories
|--------------------------------------------------------------------------
*/

$stmt = $conn->prepare("
    SELECT
        id,
        category_name,
        description,
        status,
        created_at
    FROM categories
    ORDER BY created_at DESC
");

$stmt->execute();

$result = $stmt->get_result();


require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>


<div class="container py-5">


    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="text-white fw-bold">
            Manage Categories
        </h2>

        <a href="dashboard.php"
           class="btn btn-outline-light">

            ← Dashboard

        </a>

    </div>


    <?php if (isset($_SESSION['category_success'])): ?>

        <div class="alert alert-success">

            <?= htmlspecialchars($_SESSION['category_success']); ?>

        </div>

        <?php unset($_SESSION['category_success']); ?>

    <?php endif; ?>


    <?php if (isset($_SESSION['category_error'])): ?>

        <div class="alert alert-danger">

            <?= htmlspecialchars($_SESSION['category_error']); ?>

        </div>

        <?php unset($_SESSION['category_error']); ?>

    <?php endif; ?>


    <div class="glass rounded-4 p-4 mb-5">

        <h4 class="text-white mb-4">
            Add New Category
        </h4>


        <form method="POST"
              action="manage-categories.php">


            <div class="mb-3">

                <label class="form-label text-white">
                    Category Name
                </label>

                <input
                    type="text"
                    name="category_name"
                    class="form-control"
                    placeholder="Example: Wedding"
                    required
                >

            </div>


            <div class="mb-3">

                <label class="form-label text-white">
                    Description
                </label>

                <textarea
                    name="description"
                    class="form-control"
                    rows="3"
                    placeholder="Enter category description"
                ></textarea>

            </div>


            <button
                type="submit"
                class="btn btn-info">

                ➕ Add Category

            </button>


        </form>

    </div>


    <div class="table-responsive">

        <table class="table table-dark table-bordered align-middle">

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Category</th>

                    <th>Description</th>

                    <th>Status</th>

                    <th>Actions</th>

                    <th>Created At</th>

                </tr>

            </thead>


            <tbody>


                <?php if ($result->num_rows > 0): ?>


                    <?php while ($category = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?= $category['id']; ?>
                            </td>


                            <td>
                                <?= htmlspecialchars(
                                    $category['category_name']
                                ); ?>
                            </td>


                            <td>
                                <?= htmlspecialchars(
                                    $category['description']
                                ); ?>
                            </td>


                            <td>

                                <?php if ($category['status'] === 'Active'): ?>

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

    <a
        href="edit-category.php?id=<?= $category['id']; ?>"
        class="btn btn-sm btn-warning mb-1"
    >
        ✏️ Edit
    </a>

    <a
        href="delete-category.php?id=<?= $category['id']; ?>"
        class="btn btn-sm btn-danger mb-1"
        onclick="return confirm('Are you sure you want to delete this category?');"
    >
        🗑️ Delete
    </a>

</td>

                            <td>
                                <?= htmlspecialchars(
                                    $category['created_at']
                                ); ?>
                            </td>

                        </tr>

                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td colspan="6"
                            class="text-center">

                            No categories found.

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