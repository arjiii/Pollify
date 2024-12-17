<?php
session_start();
require_once '../includes/db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = 'Please log in first to register as a candidate.';
    header('Location: ../voting/login-page.php');
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Create database connection
        $db = new Database();
        $conn = $db->connect();
        
        // Get form data and sanitize
        $position = trim($_POST['position'] ?? '');
        $platform = trim($_POST['platform'] ?? '');
        
        // Basic validation
        if (empty($position) || empty($platform)) {
            throw new Exception("Please fill in all required fields");
        }

        // Get user details from session
        $user_id = $_SESSION['user_id'];
        
        // Check if user is already registered as a candidate
        $stmt = $conn->prepare("SELECT id FROM candidates WHERE user_id = ?");
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            throw new Exception("You have already registered as a candidate");
        }

        // Generate unique candidate ID
        $candidate_id = uniqid('cand_');

        // Insert into candidates table
        $stmt = $conn->prepare("
            INSERT INTO candidates (
                id, user_id, position, platform, status
            ) VALUES (?, ?, ?, ?, 'pending')
        ");

        $stmt->bind_param("ssss", $candidate_id, $user_id, $position, $platform);

        if (!$stmt->execute()) {
            throw new Exception("Error registering as candidate: " . $stmt->error);
        }

        // Store success message
        $_SESSION['success'] = "Your candidacy registration has been submitted successfully! Your application is pending approval.";
        
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

// Get user details for the form
try {
    $db = new Database();
    $conn = $db->connect();
    $stmt = $conn->prepare("
        SELECT first_name, last_name, student_id, email, course, year_level 
        FROM users 
        WHERE id = ?
    ");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
} catch (Exception $e) {
    $_SESSION['error'] = "Error fetching user details: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gordon College Student Council Registration</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-green-50 to-yellow-50">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-xl w-full space-y-8 bg-white p-8 rounded-xl shadow-lg">
            <!-- Header with Gordon College Logo -->
            <div class="text-center">
                <img src="\assets\309700406_456647136493335_6192622109565785850_n.jpg" alt="Gordon College Logo" class="mx-auto h-32 w-32 object-contain"/>
                <h2 class="mt-4 text-3xl font-extrabold text-gray-900">
                    Student Council Candidacy Registration
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Excellence • Character • Service
                </p>
                <p class="text-sm text-gray-500">
                    Academic Year 2024-2025
                </p>
            </div>

            <!-- Success/Error Messages -->
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Candidate Information (Read-only) -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Your Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Name</p>
                        <p class="mt-1"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Student ID</p>
                        <p class="mt-1"><?php echo htmlspecialchars($user['student_id']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Course</p>
                        <p class="mt-1"><?php echo htmlspecialchars($user['course']); ?></p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Year Level</p>
                        <p class="mt-1"><?php echo htmlspecialchars($user['year_level']); ?></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm font-medium text-gray-500">Email</p>
                        <p class="mt-1"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                </div>
            </div>

            <!-- Registration Form -->
            <form class="mt-8 space-y-6" method="POST" action="" enctype="multipart/form-data">
                <!-- Position -->
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">
                        Position Running For
                    </label>
                    <select 
                        id="position" 
                        name="position" 
                        required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                    >
                        <option value="">Select desired position</option>
                        <option value="president">President</option>
                        <option value="vice-president">Vice President</option>
                        <option value="secretary">Secretary</option>
                        <option value="treasurer">Treasurer</option>
                        <option value="auditor">Auditor</option>
                        <option value="pro">Public Relations Officer</option>
                        <option value="representative">Department Representative</option>
                    </select>
                </div>

                <!-- Platform/Goals -->
                <div>
                    <label for="platform" class="block text-sm font-medium text-gray-700">
                        Platform and Goals
                    </label>
                    <textarea 
                        id="platform" 
                        name="platform" 
                        rows="4" 
                        required 
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500"
                        placeholder="Describe your platform and goals for the student council..."
                    ></textarea>
                </div>

                <!-- Photo Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">
                        Candidate Photo (2x2)
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none">
                                    <span>Upload a photo</span>
                                    <input id="photo" name="photo" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">
                                PNG, JPG up to 5MB
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Declaration -->
                <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input
                            id="declaration"
                            name="declaration"
                            type="checkbox"
                            required
                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                        >
                    </div>
                    <div class="ml-3">
                        <label for="declaration" class="text-sm text-gray-600">
                            I declare that all information provided is true and accurate. I understand and will abide by the election rules and guidelines of Gordon College.
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button 
                        type="submit" 
                        class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150 ease-in-out"
                    >
                        Submit Candidacy
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>