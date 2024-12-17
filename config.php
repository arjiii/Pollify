<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'pollify');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
if ($conn->query($sql) === TRUE) {
    $conn->select_db(DB_NAME);
} else {
    die("Error creating database: " . $conn->error);
}

// Create users table with additional fields
$sql = "CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(255) PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    student_id VARCHAR(50) UNIQUE NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    course VARCHAR(255) NOT NULL,
    year_level INT NOT NULL,
    hashed_password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student',
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (!$conn->query($sql)) {
    die("Error creating users table: " . $conn->error);
}

// Create elections table (renamed from polls for clarity)
$sql = "CREATE TABLE IF NOT EXISTS elections (
    id VARCHAR(255) PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    election_type ENUM('class_representative', 'student_council', 'department_head') NOT NULL,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status ENUM('draft', 'active', 'completed', 'cancelled') DEFAULT 'draft',
    created_by VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
)";

if (!$conn->query($sql)) {
    die("Error creating elections table: " . $conn->error);
}

// Create candidates table with enhanced fields
$sql = "CREATE TABLE IF NOT EXISTS candidates (
    id VARCHAR(255) PRIMARY KEY,
    election_id VARCHAR(255) NOT NULL,
    user_id VARCHAR(255) NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    student_id VARCHAR(50) NOT NULL,
    course VARCHAR(255) NOT NULL,
    year_level INT NOT NULL,
    position VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    platform TEXT,
    qualifications TEXT,
    photo_url VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected', 'withdrawn') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (election_id) REFERENCES elections(id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    UNIQUE KEY unique_candidate_election (election_id, user_id)
)";

if (!$conn->query($sql)) {
    die("Error creating candidates table: " . $conn->error);
}

// Create votes table with anonymity considerations
$sql = "CREATE TABLE IF NOT EXISTS votes (
    id VARCHAR(255) PRIMARY KEY,
    election_id VARCHAR(255) NOT NULL,
    candidate_id VARCHAR(255) NOT NULL,
    voter_hash VARCHAR(255) NOT NULL, -- Hashed voter ID for anonymity
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (election_id) REFERENCES elections(id),
    FOREIGN KEY (candidate_id) REFERENCES candidates(id),
    UNIQUE KEY unique_vote (election_id, voter_hash)
)";

if (!$conn->query($sql)) {
    die("Error creating votes table: " . $conn->error);
}

// Create notifications table for the notification system
$sql = "CREATE TABLE IF NOT EXISTS notifications (
    id VARCHAR(255) PRIMARY KEY,
    user_id VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type ENUM('info', 'success', 'warning', 'error') DEFAULT 'info',
    read_status BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if (!$conn->query($sql)) {
    die("Error creating notifications table: " . $conn->error);
}

// Create audit_log table for tracking important actions
$sql = "CREATE TABLE IF NOT EXISTS audit_log (
    id VARCHAR(255) PRIMARY KEY,
    user_id VARCHAR(255),
    action VARCHAR(255) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
)";

if (!$conn->query($sql)) {
    die("Error creating audit_log table: " . $conn->error);
} 