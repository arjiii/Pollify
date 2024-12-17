<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Create connection without database
    $conn = new mysqli($host, $username, $password);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Read and execute the SQL file
    $sql = file_get_contents('pollify.sql');
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    foreach($statements as $statement) {
        if (!empty($statement)) {
            if (!$conn->query($statement)) {
                throw new Exception("Error executing statement: " . $conn->error . "\nStatement: " . $statement);
            }
        }
    }
    
    echo "Database initialized successfully!";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
} finally {
    if (isset($conn)) {
        $conn->close();
    }
}
?> 