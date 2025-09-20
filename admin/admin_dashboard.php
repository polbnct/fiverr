<?php 
// Your original require_once path is kept as is.
require_once '/xampp/htdocs/QuickProgrammingOOPTutorials/2526_0906_lecforsoftenglec/2_fiverr_clone/client/classloader.php'; 

// Security Check: Only allow users with the 'fiverr_administrator' role
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'fiverr_administrator') {
    // Redirect non-admins to the homepage
    header("Location: ../index.php");
    exit();
}

$categories = $categoryObj->getAllCategories(); // For the 'Add Subcategory' dropdown
$categoryTree = $categoryObj->getAllCategoriesWithSubcategories(); // For the management list below
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Manage Categories</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-5">
        <h2 class="mb-4 text-center">Admin Dashboard</h2>

        <!-- Display Session Feedback Messages -->
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?php echo $_SESSION['status'] == '200' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['message']; unset($_SESSION['message']); unset($_SESSION['status']); ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="row">
            <!-- Add New Category Form (Unchanged) -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Add New Category</h4>
                    </div>
                    <div class="card-body">
                        <form action="../client/core/handleForms.php" method="POST">
                            <div class="form-group">
                                <label for="category_name">Category Name</label>
                                <input type="text" class="form-control" name="category_name" placeholder="e.g., Technology" required>
                            </div>
                            <button type="submit" name="addCategoryBtn" class="btn btn-primary">Add Category</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Add New Subcategory Form (Unchanged) -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Add New Subcategory</h4>
                    </div>
                    <div class="card-body">
                        <form action="../client/core/handleForms.php" method="POST">
                            <div class="form-group">
                                <label for="category_id">Parent Category</label>
                                <select class="form-control" name="category_id" required>
                                    <option value="" disabled selected>-- Select a Category --</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['category_id']; ?>">
                                            <?php echo htmlspecialchars($category['category_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="subcategory_name">Subcategory Name</label>
                                <input type="text" class="form-control" name="subcategory_name" placeholder="e.g., Web Development" required>
                            </div>
                            <button type="submit" name="addSubcategoryBtn" class="btn btn-secondary">Add Subcategory</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Manage Existing Categories</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($categoryTree)): ?>
                            <p class="text-center">No categories found. Add one above to get started.</p>
                        <?php else: ?>
                            <ul class="list-group">
                                <?php foreach ($categoryTree as $category): ?>
                                    <!-- Parent Category Item -->
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong><?php echo htmlspecialchars($category['category_name']); ?></strong>
                                        <form action="../client/core/handleForms.php" method="POST" class="ml-2">
                                            <input type="hidden" name="category_id" value="<?php echo $category['category_id']; ?>">
                                            <button type="submit" name="deleteCategoryBtn" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this entire category and ALL its subcategories? This cannot be undone.')">Delete</button>
                                        </form>
                                    </li>
                                    <!-- Subcategory Items -->
                                    <?php if (!empty($category['subcategories'])): ?>
                                        <?php foreach ($category['subcategories'] as $subcategory): ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center pl-5">
                                                <span><?php echo htmlspecialchars($subcategory['subcategory_name']); ?></span>
                                                <form action="../client/core/handleForms.php" method="POST" class="ml-2">
                                                    <input type="hidden" name="subcategory_id" value="<?php echo $subcategory['subcategory_id']; ?>">
                                                    <button type="submit" name="deleteSubcategoryBtn" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to delete this subcategory?')">Delete</button>
                                                </form>
                                            </li>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and its dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>