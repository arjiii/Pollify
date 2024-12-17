<!-- login_process.php -->
<?php
session_start();
require_once '../includes/db_connection.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $student_id = trim($_POST['student_id'] ?? '');
        $password = trim($_POST['password'] ?? '');
        
        // Validate input
        if (empty($student_id) || empty($password)) {
            throw new Exception("Please fill in all fields");
        }

        // Create database connection
        $db = new Database();
        $conn = $db->connect();

        // Get user from database
        $stmt = $conn->prepare("
            SELECT id, student_id, first_name, last_name, hashed_password, role, status 
            FROM users 
            WHERE student_id = ?
            LIMIT 1
        ");

        $stmt->execute([$student_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['hashed_password'])) {
            // Check if account is active
            if ($user['status'] !== 'active') {
                throw new Exception("Your account is not active. Please contact the administrator.");
            }

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['student_id'] = $user['student_id'];
            $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['logged_in'] = true;

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header('Location: ../admin/dashboard.php');
            } else {
                header('Location: ../dashboard.php');
            }
            exit;
        } else {
            throw new Exception("Invalid student ID or password");
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}

header('Location: login-page.php');
exit;
?>