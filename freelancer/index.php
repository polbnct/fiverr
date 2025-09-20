<?php 
require_once './classloader.php'; 

if (!$userObj->isLoggedIn()) {
  header("Location: login.php");
  exit(); 
}

if (!$userObj->isFreelancer()) {
  header("Location: ../client/index.php");
  exit();
} 

$categories = $categoryObj->getAllCategories();
?>
<!doctype html>
  <html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    
    <!-- Use the FULL version of jQuery, not the slim one. Placed here in the head. -->
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    
    <style>
      body {
        font-family: "Arial";
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>
    <div class="container-fluid">
      <div class="display-4 text-center">Hello there and welcome! <span class="text-success"><?php echo $_SESSION['username']; ?></span>. Add Proposal Here!</div>
      <div class="row">
        <div class="col-md-5">
          <div class="card mt-4 mb-4">
            <div class="card-body">
              <!-- Changed action path to be correct for this file's location -->
              <form action="core/handleForms.php" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                  <?php  
                  if (isset($_SESSION['message']) && isset($_SESSION['status'])) {
                    if ($_SESSION['status'] == "200") {
                      echo "<h1 style='color: green;'>{$_SESSION['message']}</h1>";
                    } else {
                      echo "<h1 style='color: red;'>{$_SESSION['message']}</h1>"; 
                    }
                  }
                  unset($_SESSION['message']);
                  unset($_SESSION['status']);
                  ?>
                  <h1 class="mb-4 mt-4">Add Proposal Here!</h1>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Description</label>
                    <input type="text" class="form-control" name="description" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Minimum Price</label>
                    <input type="number" class="form-control" name="min_price" required>
                  </div>
                  <div class="form-group">
                    <label for="exampleInputEmail1">Max Price</label>
                    <input type="number" class="form-control" name="max_price" required>
                  </div>

                  <div class="row">
                      <div class="form-group col-md-6">
                          <label for="category_id">Category</label>
                          <select class="form-control" id="category_select" name="category_id" required>
                              <option value="" disabled selected>-- Select a Category --</option>
                              <?php foreach ($categories as $category): ?>
                                  <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['category_name']); ?></option>
                              <?php endforeach; ?>
                          </select>
                      </div>
                      <div class="form-group col-md-6">
                          <label for="subcategory_id">Subcategory</label>
                          <select class="form-control" id="subcategory_select" name="subcategory_id" required>
                              <option value="" disabled selected>-- Select Category First --</option>
                          </select>
                      </div>
                  </div>

                  <div class="form-group">
                    <label for="exampleInputEmail1">Image</label>
                    <input type="file" class="form-control" name="image" required>
                    <input type="submit" class="btn btn-primary float-right mt-4" name="insertProposalBtn">
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="col-md-7">
          <?php $getProposals = $proposalObj->getProposals(); ?>
          <?php foreach ($getProposals as $proposal) { ?>
          <div class="card shadow mt-4 mb-4">
            <div class="card-body">
              <h2><a href="other_profile_view.php?user_id=<?php echo $proposal['user_id']; ?>"><?php echo $proposal['username']; ?></a></h2>
              <img src="<?php echo '../images/' . $proposal['image']; ?>" alt="" class="img-fluid">
              <p class="mt-4"><i><?php echo $proposal['proposals_date_added']; ?></i></p>
              <p class="mt-2"><?php echo $proposal['description']; ?></p>
              <h4><i><?php echo number_format($proposal['min_price']) . " - " . number_format($proposal['max_price']); ?> PHP</i></h4>
              <div class="float-right">
                <a href="#">Check out services</a>
              </div>
            </div>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <script>
    $(document).ready(function() {
        $('#category_select').on('change', function() {
            var categoryId = $(this).val();
            var subcategorySelect = $('#subcategory_select');
            subcategorySelect.html('<option value="">Loading...</option>');
            if (categoryId) {
                $.ajax({

                    url: '../ajax_get_subcategories.php',
                    type: 'POST',
                    data: { category_id: categoryId },
                    dataType: 'json',
                    success: function(data) {
                        subcategorySelect.html('<option value="" disabled selected>-- Select a Subcategory --</option>');
                        if (data.length > 0) {
                            $.each(data, function(key, value) {
                                subcategorySelect.append('<option value="' + value.subcategory_id + '">' + value.subcategory_name + '</option>');
                            });
                        } else {
                            subcategorySelect.html('<option value="" disabled>-- No subcategories --</option>');
                        }
                    },
                    error: function() {
                        subcategorySelect.html('<option value="">Error loading data</option>');
                    }
                });
            } else {
                subcategorySelect.html('<option value="" disabled selected>-- Select Category First --</option>');
            }
        });
    });
    </script>
  </body>
</html>