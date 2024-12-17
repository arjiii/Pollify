<?php
require_once '../includes/session.php';
require_once '../includes/functions.php';

// Check if user is logged in
checkLogin();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verify CSRF token
    verifyCSRFToken($_POST['csrf_token']);
    
    $electionId = trim($_POST['election_id']);
    $candidateId = trim($_POST['candidate_id']);
    
    try {
        // Validate input
        if (empty($electionId) || empty($candidateId)) {
            throw new Exception("Invalid input parameters");
        }
        
        // Cast vote
        if (castVote($electionId, $candidateId, $_SESSION['user_id'])) {
            // Create success notification
            createNotification(
                $_SESSION['user_id'],
                "Vote Cast Successfully",
                "Your vote has been recorded successfully.",
                "success"
            );
            
            $_SESSION['success'] = "Your vote has been cast successfully!";
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        error_log("Voting error: " . $e->getMessage());
    }
    
    // Redirect back to the election page
    header("Location: view-election.php?id=" . $electionId);
    exit;
}

// If accessed directly without POST
header("Location: dashboard.php");
exit; 