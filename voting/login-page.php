<?php
session_start();
require_once '../includes/db_connection.php';

$error = null;
$success = null;

try {
    $database = new Database();
    $conn = $database->connect();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {
        // Get registration data
        $first_name = trim($_POST['first_name'] ?? '');
        $last_name = trim($_POST['last_name'] ?? '');
        $student_id = trim($_POST['student_id'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $year_level = (int)($_POST['year_level'] ?? 0);
        $password = trim($_POST['password'] ?? '');
        $confirm_password = trim($_POST['confirm_password'] ?? '');

        // Validate input
        if (empty($first_name) || empty($last_name) || empty($student_id) || 
            empty($email) || empty($course) || empty($year_level) || 
            empty($password) || empty($confirm_password)) {
            throw new Exception("All fields are required");
        }

        if ($password !== $confirm_password) {
            throw new Exception("Passwords do not match");
        }

        if (strlen($password) < 6) {
            throw new Exception("Password must be at least 6 characters long");
        }

        // Check if student ID already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE student_id = ?");
        $stmt->execute([$student_id]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Student ID already registered");
        }

        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->rowCount() > 0) {
            throw new Exception("Email already registered");
        }

        // Generate unique user ID and hash password
        $user_id = uniqid('user_');
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user
        $stmt = $conn->prepare("
            INSERT INTO users (
                id, email, student_id, first_name, last_name,
                course, year_level, hashed_password, role, status
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'student', 'active')
        ");

        $success = $stmt->execute([
            $user_id, $email, $student_id, $first_name, $last_name,
            $course, $year_level, $hashed_password
        ]);

        if ($success) {
            $_SESSION['success'] = "Registration successful! Please log in with your credentials.";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit;
        } else {
            throw new Exception("Error registering user");
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pollify - Student Voting System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/c473da0646.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .auth-card {
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.75);
            border-radius: 12px;
            border: 1px solid rgba(209, 213, 219, 0.3);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-100 via-purple-50 to-teal-100 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="bg-white p-3 rounded-full shadow-lg">
                    <i class="fas fa-vote-yea text-4xl text-indigo-600"></i>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome to Pollify</h1>
            <p class="text-gray-600">Your voice matters. Vote securely.</p>
        </div>

        <!-- Error Message -->
        <?php if ($error): ?>
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?php echo htmlspecialchars($error); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Success Message -->
        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">
                            <?php 
                            echo htmlspecialchars($_SESSION['success']);
                            unset($_SESSION['success']);
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Tabs -->
        <div class="mb-6">
            <div class="flex border-b border-gray-200">
                <button onclick="showTab('login')" id="loginTab" class="flex-1 py-2 px-4 text-center border-b-2 font-medium text-sm focus:outline-none">
                    Login
                </button>
                <button onclick="showTab('register')" id="registerTab" class="flex-1 py-2 px-4 text-center border-b-2 font-medium text-sm focus:outline-none">
                    Register
                </button>
            </div>
        </div>

        <!-- Login Form -->
        <div id="loginForm" class="auth-card p-8 shadow-2xl">
            <form action="login-process.php" method="POST" class="space-y-6">
                <!-- Student ID -->
                <div>
                    <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                    <input type="text" id="student_id" name="student_id" required
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Enter your student ID">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Enter your password">
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <input type="checkbox" id="remember_me" name="remember_me"
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="remember_me" class="ml-2 text-gray-600">Remember me</label>
                    </div>
                </div>

                <!-- Login Button -->
                <button type="submit"
                    class="w-full flex justify-center items-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Sign in
                </button>
            </form>
        </div>

        <!-- Registration Form -->
        <div id="registerForm" class="auth-card p-8 shadow-2xl hidden">
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="space-y-6">
                <input type="hidden" name="action" value="register">

                <!-- Name Fields -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                        <input type="text" id="first_name" name="first_name" required
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="First name">
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                        <input type="text" id="last_name" name="last_name" required
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Last name">
                    </div>
                </div>

                <!-- Student ID -->
                <div>
                    <label for="reg_student_id" class="block text-sm font-medium text-gray-700 mb-1">Student ID</label>
                    <input type="text" id="reg_student_id" name="student_id" required
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Enter your student ID">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="email" name="email" required
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="your.email@example.com">
                </div>

                <!-- Course -->
                <div>
                    <label for="course" class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                    <select id="course" name="course" required
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Select your course</option>
                        <option value="BSIT">Bachelor of Science in Information Technology</option>
                        <option value="BSBA">Bachelor of Science in Business Administration</option>
                        <option value="BEED">Bachelor of Elementary Education</option>
                        <option value="BSED">Bachelor of Secondary Education</option>
                        <option value="BSCRIM">Bachelor of Science in Criminology</option>
                    </select>
                </div>

                <!-- Year Level -->
                <div>
                    <label for="year_level" class="block text-sm font-medium text-gray-700 mb-1">Year Level</label>
                    <select id="year_level" name="year_level" required
                        class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Select your year level</option>
                        <option value="1">1st Year</option>
                        <option value="2">2nd Year</option>
                        <option value="3">3rd Year</option>
                        <option value="4">4th Year</option>
                    </select>
                </div>

                <!-- Password -->
                <div>
                    <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <input type="password" id="reg_password" name="password" required
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Create a password">
                        <button type="button" onclick="togglePassword('reg_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                    <div class="relative">
                        <input type="password" id="confirm_password" name="confirm_password" required
                            class="block w-full px-4 py-3 rounded-lg border border-gray-300 shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="Confirm your password">
                        <button type="button" onclick="togglePassword('confirm_password')" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Register Button -->
                <button type="submit"
                    class="w-full flex justify-center items-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <i class="fas fa-user-plus mr-2"></i>
                    Register
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-gray-600 text-sm">
            <p>&copy; <?php echo date('Y'); ?> Pollify. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            const icon = input.nextElementSibling.querySelector('i');
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }

        // Tab switching
        function showTab(tabName) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const loginTab = document.getElementById('loginTab');
            const registerTab = document.getElementById('registerTab');

            if (tabName === 'login') {
                loginForm.classList.remove('hidden');
                registerForm.classList.add('hidden');
                loginTab.classList.add('border-indigo-500', 'text-indigo-600');
                registerTab.classList.remove('border-indigo-500', 'text-indigo-600');
            } else {
                loginForm.classList.add('hidden');
                registerForm.classList.remove('hidden');
                loginTab.classList.remove('border-indigo-500', 'text-indigo-600');
                registerTab.classList.add('border-indigo-500', 'text-indigo-600');
            }
        }

        // Initialize tabs
        document.addEventListener('DOMContentLoaded', function() {
            showTab('login');
        });
    </script>
</body>
</html>