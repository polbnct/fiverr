<?php  

require_once 'Database.php';

class User extends Database {

    public function startSession() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function usernameExists($username) {
        $sql = "SELECT COUNT(*) as username_count FROM fiverr_clone_users WHERE username = ?";
        $count = $this->executeQuerySingle($sql, [$username]);
        return $count['username_count'] > 0;
    }
    
    /**
     * Registers a new user with the 'client' role.
     */
    public function registerUser($username, $email, $password, $contact_number) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        // UPDATED: Now inserts a 'user_role' instead of 'is_client'
        $sql = "INSERT INTO fiverr_clone_users (username, email, password, contact_number, user_role) VALUES (?, ?, ?, ?, 'client')";
        try {
            return $this->executeNonQuery($sql, [$username, $email, $hashed_password, $contact_number]);
        } catch (\PDOException $e) {
            return false;
        }
    }

    public function loginUser($email, $password) {
        // UPDATED: Fetches 'user_role' instead of 'is_client'
        $sql = "SELECT user_id, username, password, user_role FROM fiverr_clone_users WHERE email = ?";
        $user = $this->executeQuerySingle($sql, [$email]);

        if ($user && password_verify($password, $user['password'])) {
            $this->startSession();
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['user_role'];
            return $user; // Return user data on success
        }
        return false;
    }

    public function isLoggedIn() {
        $this->startSession();
        return isset($_SESSION['user_id']);
    }
    
    public function isAdmin() {
        $this->startSession();
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'fiverr_administrator';
    }

    /**
     * Checks if the logged-in user's role is 'client'.
     */
    public function isClient() {
        $this->startSession();
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'client';
    }

    /**
     * Checks if the logged-in user's role is 'freelancer'.
     */
    public function isFreelancer() {
        $this->startSession();
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'freelancer';
    }

    public function logout() {
        $this->startSession();
        session_unset();
        session_destroy();
    }

    public function getUsers($id = null) {
        if ($id) {
            $sql = "SELECT * FROM fiverr_clone_users WHERE user_id = ?";
            return $this->executeQuerySingle($sql, [$id]);
        }
        $sql = "SELECT * FROM fiverr_clone_users";
        return $this->executeQuery($sql);
    }

    public function updateUser($contact_number, $bio_description, $user_id, $display_picture="") {
        if (empty($display_picture)) {
            $sql = "UPDATE fiverr_clone_users SET contact_number = ?, bio_description = ? WHERE user_id = ?";
            return $this->executeNonQuery($sql, [$contact_number, $bio_description, $user_id]);
        }
    }

    public function deleteUser($id) {
        $sql = "DELETE FROM fiverr_clone_users WHERE user_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>