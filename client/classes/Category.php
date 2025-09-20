<?php
// We assume your Database.php is in the same directory or loaded by the classloader.
// If it's in a different directory, adjust the require_once path.
require_once 'Database.php';

class Category extends Database {

    /**
     * The constructor calls the parent constructor to establish the PDO connection.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Creates a new main category using the inherited executeNonQuery method.
     */
    public function createCategory($category_name) {
        $sql = "INSERT INTO categories (category_name) VALUES (?)";
        return $this->executeNonQuery($sql, [$category_name]);
    }

    /**
     * Creates a new subcategory linked to a parent category.
     */
    public function createSubcategory($category_id, $subcategory_name) {
        $sql = "INSERT INTO subcategories (category_id, subcategory_name) VALUES (?, ?)";
        return $this->executeNonQuery($sql, [$category_id, $subcategory_name]);
    }

    /**
     * Fetches all main categories using the inherited executeQuery method.
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY category_name ASC";
        return $this->executeQuery($sql);
    }

    /**
     * Fetches all subcategories for a given category ID.
     */
    public function getSubcategoriesByCategoryId($category_id) {
        $sql = "SELECT * FROM subcategories WHERE category_id = ? ORDER BY subcategory_name ASC";
        return $this->executeQuery($sql, [$category_id]);
    }

    /**
     * Fetches all categories and their associated subcategories for the navbar.
     */
    public function getAllCategoriesWithSubcategories() {
        $categories = $this->getAllCategories();
        $full_structure = [];

        foreach ($categories as $category) {
            $subcategories = $this->getSubcategoriesByCategoryId($category['category_id']);
            $category['subcategories'] = $subcategories; // Add subcategories to the array
            $full_structure[] = $category;
        }

        return $full_structure;
    }
    public function deleteCategory($category_id) {
        $sql = "DELETE FROM categories WHERE category_id = ?";
        return $this->executeNonQuery($sql, [$category_id]);
    }

    public function deleteSubcategory($subcategory_id) {
        $sql = "DELETE FROM subcategories WHERE subcategory_id = ?";
        return $this->executeNonQuery($sql, [$subcategory_id]);
    }
}
?>