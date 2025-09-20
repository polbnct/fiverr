<?php
// We assume $categoryObj is available here from your page's classloader
// If not, you might need to instantiate it:
// $categoryObj = new Category($database_connection);
?>
<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #023E8A;">
  <a class="navbar-brand" href="index.php">Client Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <!-- Links on the left -->
    <ul class="navbar-nav mr-auto">
      <?php
      // --- NEW: FETCH AND DISPLAY CATEGORIES ---
      $categories = $categoryObj->getAllCategoriesWithSubcategories();
      foreach ($categories as $category) {
      ?>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_<?php echo $category['category_id']; ?>" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php echo htmlspecialchars($category['category_name']); ?>
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdown_<?php echo $category['category_id']; ?>">
            <?php
            if (!empty($category['subcategories'])) {
              foreach ($category['subcategories'] as $subcategory) {
            ?>
                <a class="dropdown-item" href="proposals_by_category.php?subcategory_id=<?php echo $subcategory['subcategory_id']; ?>">
                  <?php echo htmlspecialchars($subcategory['subcategory_name']); ?>
                </a>
            <?php
              }
            } else {
              echo '<a class="dropdown-item" href="#">No subcategories</a>';
            }
            ?>
          </div>
        </li>
      <?php } ?>
    </ul>

    <!-- Links on the right -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="project_offers_submitted.php">Project Offers Submitted </a>
      </li>
      <?php // --- NEW: CONDITIONAL ADMIN LINK --- ?>
      <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'fiverr_administrator') : ?>
        <li class="nav-item">
            <a class="nav-link" href="../admin/admin_dashboard.php">Admin Panel</a>
        </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>