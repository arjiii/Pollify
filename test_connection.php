<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'includes/db_connection.php';

try {
    $db = new Database();
    $conn = $db->connect();
    echo "Database connection successful!";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?> 