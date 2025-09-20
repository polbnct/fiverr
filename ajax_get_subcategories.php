<?php
// ajax_get_subcategories.php

// Start the session, as it's good practice and other classes might use it.
session_start();

// --- THIS IS THE DEFINITIVE FIX ---
// Instead of a classloader, we explicitly include the ONLY two class files this script needs.
// The paths are written from the root directory, so they will always work.
// Since the request comes from the freelancer side, we'll use its class files.
require_once 'freelancer/classes/Database.php';
require_once 'freelancer/classes/Category.php';

// Now, we manually create the object the script needs.
$categoryObj = new Category();
// ---------------------------------

// Set the content type to JSON for the response
header('Content-Type: application/json');

// Check if a category_id was sent
if (isset($_POST['category_id'])) {
    $category_id = intval($_POST['category_id']);
    
    // This will now work because the $categoryObj was created successfully.
    $subcategories = $categoryObj->getSubcategoriesByCategoryId($category_id);
    
    // Encode the result as JSON and send it back
    echo json_encode($subcategories);
} else {
    // If no category_id was provided, return an empty array
    echo json_encode([]);
}
?>