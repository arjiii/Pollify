<?php
require_once 'db_connection.php';

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generate new CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Sanitize input
 */
function sanitizeInput($input) {
    return htmlspecialchars(strip_tags(trim($input)));
}

/**
 * Check if user is logged in
 */
function isLoggedIn() {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

/**
 * Check if user is admin
 */
function isAdmin() {
    return isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

/**
 * Redirect if not logged in
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['error'] = 'Please log in to access this page.';
        header('Location: /Pollify/voting/login-page.php');
        exit;
    }
}

/**
 * Redirect if not admin
 */
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        $_SESSION['error'] = 'Access denied. Admin privileges required.';
        header('Location: /Pollify/dashboard.php');
        exit;
    }
}

/**
 * Create a notification
 */
function createNotification($user_id, $title, $message, $type = 'info') {
    try {
        $db = new Database();
        $conn = $db->connect();
        
        $id = uniqid('notif_', true);
        $stmt = $conn->prepare("
            INSERT INTO notifications (id, user_id, title, message, type)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$id, $user_id, $title, $message, $type]);
    } catch (Exception $e) {
        error_log("Error creating notification: " . $e->getMessage());
        return false;
    }
}

/**
 * Log an audit event
 */
function logAudit($user_id, $action, $details = null) {
    try {
        $db = new Database();
        $conn = $db->connect();
        
        $id = uniqid('audit_', true);
        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        
        $stmt = $conn->prepare("
            INSERT INTO audit_log (id, user_id, action, details, ip_address)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$id, $user_id, $action, $details, $ip]);
    } catch (Exception $e) {
        error_log("Error logging audit: " . $e->getMessage());
        return false;
    }
}

/**
 * Generate a unique ID
 */
function generateUniqueId($prefix = '') {
    return uniqid($prefix, true);
}

/**
 * Format date
 */
function formatDate($date, $format = 'Y-m-d H:i:s') {
    return date($format, strtotime($date));
}

/**
 * Check if an election is active
 */
function isElectionActive($election_id) {
    try {
        $db = new Database();
        $conn = $db->connect();
        
        $stmt = $conn->prepare("
            SELECT status 
            FROM elections 
            WHERE id = ? AND status = 'active'
            AND NOW() BETWEEN start_date AND end_date
        ");
        $stmt->execute([$election_id]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        error_log("Error checking election status: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if user has already voted
 */
function hasUserVoted($election_id, $user_id) {
    try {
        $db = new Database();
        $conn = $db->connect();
        
        $voter_hash = hash('sha256', $election_id . $user_id);
        $stmt = $conn->prepare("
            SELECT id 
            FROM votes 
            WHERE election_id = ? AND voter_hash = ?
        ");
        $stmt->execute([$election_id, $voter_hash]);
        return $stmt->rowCount() > 0;
    } catch (Exception $e) {
        error_log("Error checking if user voted: " . $e->getMessage());
        return false;
    }
}

/**
 * Get user's notifications
 */
function getUserNotifications($user_id, $limit = 5) {
    try {
        $db = new Database();
        $conn = $db->connect();
        
        $stmt = $conn->prepare("
            SELECT id, title, message, type, read_status, created_at
            FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$user_id, $limit]);
        return $stmt->fetchAll();
    } catch (Exception $e) {
        error_log("Error getting notifications: " . $e->getMessage());
        return [];
    }
}

/**
 * Mark notification as read
 */
function markNotificationAsRead($notification_id) {
    try {
        $db = new Database();
        $conn = $db->connect();
        
        $stmt = $conn->prepare("
            UPDATE notifications
            SET read_status = TRUE
            WHERE id = ?
        ");
        return $stmt->execute([$notification_id]);
    } catch (Exception $e) {
        error_log("Error marking notification as read: " . $e->getMessage());
        return false;
    }
} 