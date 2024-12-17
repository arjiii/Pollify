<?php
session_start();
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = trim($_POST['admin_id']);
    $password = trim($_POST['password']);
    
    // Validate input
    if (empty($admin_id) || empty($password)) {
        $_SESSION['error'] = 'Please fill in all fields';
        header('Location: admin-loginpage.php');
        exit;
    }

    try {
        $stmt = $conn->prepare("SELECT id, student_id, hashed_password FROM users WHERE student_id = ? AND role = 'admin'");
        if (!$stmt) {
            throw new Exception($conn->error);
        }

        $stmt->bind_param("s", $admin_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['hashed_password'])) {
                // Set session variables
                $_SESSION['user_id'] = $admin['id'];
                $_SESSION['student_id'] = $admin['student_id'];
                $_SESSION['role'] = 'admin';
                $_SESSION['logged_in'] = true;
                
                header('Location: admin-dashboard.php');
                exit;
            } else {
                $_SESSION['error'] = 'Invalid password';
            }
        } else {
            $_SESSION['error'] = 'Invalid admin credentials';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'An error occurred. Please try again later.';
        error_log("Admin login error: " . $e->getMessage());
    }

    header('Location: admin-loginpage.php');
    exit;
}

// If accessed directly without POST
header('Location: admin-loginpage.php');
exit; 