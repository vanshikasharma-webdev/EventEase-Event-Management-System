<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once '../config/database.php';

if (
    !isset($_SESSION['admin_id']) ||
    !isset($_SESSION['admin_role']) ||
    $_SESSION['admin_role'] !== 'Admin'
) {
    header("Location: login.php");
    exit();
}

$categoryId = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($categoryId <= 0) {
    header("Location: manage-categories.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT id, category_name, description, status
    FROM categories
    WHERE id = ?
    LIMIT 1
");

$stmt->bind_param("i", $categoryId);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    $_SESSION['category_error'] = "Category not found.";
    header("Location: manage-categories.php");
    exit();
}

$category = $result->fetch_assoc();

require_once '../includes/header.php';
require_once '../includes/navbar.php';

?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="glass rounded-4 p-5">

                <h2 class="text-white fw-bold mb-4">
                    Edit Category
                </h2>

                <form action="edit-category-process.php" method="POST">

                    <input
                        type="hidden"
                        name="id"
                        value="<?= $category['id']; ?>"
                    >

                    <div class="mb-3">

                        <label class="form-label text-white">
                            Category Name
                        </label>

                        <input
                            type="text"
                            name="category_name"
                            class="form-control"
                            value="<?= htmlspecialchars($category['category_name']); ?>"
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
                            rows="4"
                        ><?= htmlspecialchars($category['description']); ?></textarea>

                    </div>

                    <div class="mb-4">

                        <label class="form-label text-white">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select"
                            required
                        >

                            <option
                                value="Active"
                                <?= $category['status'] === 'Active' ? 'selected' : ''; ?>
                            >
                                Active
                            </option>

                            <option
                                value="Inactive"
                                <?= $category['status'] === 'Inactive' ? 'selected' : ''; ?>
                            >
                                Inactive
                            </option>

                        </select>

                    </div>

                    <button
                        type="submit"
                        class="btn btn-info me-2"
                    >
                        💾 Update Category
                    </button>

                    <a
                        href="manage-categories.php"
                        class="btn btn-outline-light"
                    >
                        Cancel
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

<?php

require_once '../includes/footer.php';

?>