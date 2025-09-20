<nav class="navbar navbar-expand-lg navbar-dark p-4" style="background-color: #0077B6;">
  <a class="navbar-brand" href="index.php">Freelancer Panel</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <!-- Links on the left (Home and Categories) -->
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="index.php">Home</a>
      </li>

      <?php
      // This uses the $categoryObj that was created in your main classloader.php
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

    <!-- Links on the right (Freelancer-specific links) -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="your_proposals.php">Your Proposals</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="offers_from_clients.php">Offers From Clients</a>
      </li>
      <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'fiverr_administrator') : ?>
        <li class="nav-item">
            <a class="nav-link" href="../admin/admin_dashboard.php">Admin Panel</a>
        </li>
      <?php endif; ?>
      <li class="nav-item">
        <a class="nav-link" href="profile.php">Profile</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="./core/handleForms.php?logoutUserBtn=1">Logout</a>
      </li>
    </ul>
  </div>
</nav>