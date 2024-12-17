<?php
require_once '../includes/session.php';
require_once '../includes/functions.php';

// Check if user is logged in
checkLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token
    verifyCSRFToken($_POST['csrf_token']);
    
    try {
        // Get form data
        $electionId = trim($_POST['election_id']);
        $position = trim($_POST['position']);
        $platform = trim($_POST['platform']);
        $qualifications = trim($_POST['qualifications']);
        
        // Validate required fields
        if (empty($electionId) || empty($position) || empty($platform) || empty($qualifications)) {
            throw new Exception("All fields are required");
        }
        
        // Check eligibility
        $eligibility = checkCandidateEligibility($_SESSION['user_id'], $electionId);
        if (!$eligibility['eligible']) {
            throw new Exception("You are not eligible to run for this position: " . implode(", ", $eligibility['reasons']));
        }
        
        // Get user details
        $stmt = $conn->prepare("SELECT full_name, student_id, course, year_level, email FROM users WHERE id = ?");
        $stmt->bind_param("s", $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        
        // Handle photo upload
        $photoUrl = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../uploads/candidates/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $fileExtension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            
            if (!in_array($fileExtension, $allowedExtensions)) {
                throw new Exception("Invalid file type. Only JPG, JPEG, and PNG files are allowed.");
            }
            
            $photoUrl = $uploadDir . bin2hex(random_bytes(16)) . '.' . $fileExtension;
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photoUrl)) {
                throw new Exception("Failed to upload photo");
            }
        }
        
        // Start transaction
        $conn->begin_transaction();
        
        // Insert candidate record
        $id = bin2hex(random_bytes(16));
        $stmt = $conn->prepare("
            INSERT INTO candidates (
                id, election_id, user_id, full_name, student_id, 
                course, year_level, position, email, platform, 
                qualifications, photo_url
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->bind_param(
            "ssssssssssss",
            $id, $electionId, $_SESSION['user_id'], $user['full_name'],
            $user['student_id'], $user['course'], $user['year_level'],
            $position, $user['email'], $platform, $qualifications, $photoUrl
        );
        
        if (!$stmt->execute()) {
            throw new Exception("Failed to submit candidacy");
        }
        
        // Create notification for admin
        createNotification(
            'admin', // You might want to get actual admin ID
            "New Candidate Registration",
            "A new candidate has registered for " . $position . ": " . $user['full_name'],
            "info"
        );
        
        // Log the action
        logAudit(
            $_SESSION['user_id'],
            "CANDIDATE_REGISTRATION",
            "Registered as candidate for position: " . $position
        );
        
        $conn->commit();
        
        // Create success notification for user
        createNotification(
            $_SESSION['user_id'],
            "Candidacy Submitted",
            "Your candidacy has been submitted and is pending approval.",
            "success"
        );
        
        $_SESSION['success'] = "Your candidacy has been submitted successfully and is pending approval.";
        header("Location: candidate-registration.php");
        exit;
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error'] = $e->getMessage();
        error_log("Candidate registration error: " . $e->getMessage());
        header("Location: candidate-registration.php");
        exit;
    }
}

// If accessed directly without POST
header("Location: candidate-registration.php");
exit; 