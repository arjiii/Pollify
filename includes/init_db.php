<?php
require_once 'db_connection.php';

try {
    // Create users table
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
        remember_token VARCHAR(255) DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $conn->query($sql);

    // Create elections table
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
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (created_by) REFERENCES users(id)
    )";
    $conn->query($sql);

    // Create candidates table
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
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (election_id) REFERENCES elections(id),
        FOREIGN KEY (user_id) REFERENCES users(id),
        UNIQUE KEY unique_candidate_election (election_id, user_id)
    )";
    $conn->query($sql);

    // Create votes table
    $sql = "CREATE TABLE IF NOT EXISTS votes (
        id VARCHAR(255) PRIMARY KEY,
        election_id VARCHAR(255) NOT NULL,
        candidate_id VARCHAR(255) NOT NULL,
        voter_hash VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (election_id) REFERENCES elections(id),
        FOREIGN KEY (candidate_id) REFERENCES candidates(id),
        UNIQUE KEY unique_vote (election_id, voter_hash)
    )";
    $conn->query($sql);

    // Create notifications table
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
    $conn->query($sql);

    // Create audit_log table
    $sql = "CREATE TABLE IF NOT EXISTS audit_log (
        id VARCHAR(255) PRIMARY KEY,
        user_id VARCHAR(255),
        action VARCHAR(255) NOT NULL,
        details TEXT,
        ip_address VARCHAR(45),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";
    $conn->query($sql);

    // Insert default admin user if not exists
    $admin_id = 'admin1';
    $admin_email = 'admin@pollify.com';
    $admin_student_id = 'ADMIN001';
    $admin_password = password_hash('password', PASSWORD_DEFAULT);

    $stmt = $conn->prepare("
        INSERT IGNORE INTO users (
            id, email, student_id, full_name, course, year_level, 
            hashed_password, role, status
        ) VALUES (
            ?, ?, ?, 'System Administrator', 'ADMIN', 4, 
            ?, 'admin', 'active'
        )
    ");
    $stmt->bind_param("ssss", $admin_id, $admin_email, $admin_student_id, $admin_password);
    $stmt->execute();

    echo "Database initialized successfully!";
} catch (Exception $e) {
    die("Database initialization failed: " . $e->getMessage());
} 