<!-- register_process.php -->
<?php
session_start();
require_once '../config.php';
require_once '../includes/session.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token
    verifyCSRFToken($_POST['csrf_token']);

    // Get and sanitize form data
    $email = trim($_POST['email']);
    $student_id = trim($_POST['student_id']);
    $full_name = trim($_POST['full_name']);
    $course = trim($_POST['course']);
    $year_level = (int)$_POST['year_level'];
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validation
    $errors = [];
    
    if (empty($email) || empty($student_id) || empty($full_name) || empty($course) || empty($year_level) || empty($password) || empty($confirm_password)) {
        $errors[] = 'All fields are required';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (strlen($password) < 8) {
        $errors[] = 'Password must be at least 8 characters long';
    }

    if ($password !== $confirm_password) {
        $errors[] = 'Passwords do not match';
    }

    if ($year_level < 1 || $year_level > 4) {
        $errors[] = 'Invalid year level';
    }

    // Check if email or student ID already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? OR student_id = ?");
    $stmt->bind_param("ss", $email, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $errors[] = 'Email or Student ID already registered';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['form_data'] = [
            'email' => $email,
            'student_id' => $student_id,
            'full_name' => $full_name,
            'course' => $course,
            'year_level' => $year_level
        ];
        header('Location: register.php');
        exit;
    }

    try {
        // Generate unique ID
        $id = bin2hex(random_bytes(16));
        
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Start transaction
        $conn->begin_transaction();

        // Insert new user
        $stmt = $conn->prepare("
            INSERT INTO users (
                id, email, student_id, full_name, course, 
                year_level, hashed_password
            ) VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssssss",
            $id, $email, $student_id, $full_name,
            $course, $year_level, $hashed_password
        );
        
        if (!$stmt->execute()) {
            throw new Exception($stmt->error);
        }

        // Log the registration
        logAudit($id, "USER_REGISTRATION", "New user registration: " . $student_id);
        
        // Create welcome notification
        createNotification(
            $id,
            "Welcome to Pollify",
            "Your account has been created successfully. You can now participate in elections.",
            "success"
        );

        $conn->commit();
        
        $_SESSION['success'] = 'Registration successful! Please login.';
        header('Location: login-page.php');
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = 'Registration failed. Please try again.';
        error_log("Registration error: " . $e->getMessage());
        header('Location: register.php');
        exit;
    }
}

// If accessed directly without POST
header('Location: register.php');
exit;