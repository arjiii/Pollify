<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Pollify</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h1 class="text-center text-3xl font-bold text-indigo-600">Pollify</h1>
            <h2 class="mt-3 text-center text-2xl font-bold text-gray-900">Create your account</h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <?php
                session_start();
                require_once '../includes/session.php';

                // Display error messages
                if (isset($_SESSION['errors'])) {
                    foreach ($_SESSION['errors'] as $error) {
                        echo '<div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">';
                        echo $error;
                        echo '</div>';
                    }
                    unset($_SESSION['errors']);
                }

                // Display success message
                if (isset($_SESSION['success'])) {
                    echo '<div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">';
                    echo $_SESSION['success'];
                    echo '</div>';
                    unset($_SESSION['success']);
                }

                // Retrieve form data if available
                $formData = $_SESSION['form_data'] ?? [];
                unset($_SESSION['form_data']);
                ?>

                <form action="register-process.php" method="POST" class="space-y-6">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" required 
                            value="<?php echo htmlspecialchars($formData['email'] ?? ''); ?>"
                            class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required 
                            value="<?php echo htmlspecialchars($formData['full_name'] ?? ''); ?>"
                            class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
                        <input type="text" id="student_id" name="student_id" required 
                            value="<?php echo htmlspecialchars($formData['student_id'] ?? ''); ?>"
                            class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="course" class="block text-sm font-medium text-gray-700">Course</label>
                        <select id="course" name="course" required 
                            class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select your course</option>
                            <option value="BSIT">Bachelor of Science in Information Technology</option>
                            <option value="BSBA">Bachelor of Science in Business Administration</option>
                            <option value="BEED">Bachelor of Elementary Education</option>
                            <option value="BSED">Bachelor of Secondary Education</option>
                            <option value="BSCRIM">Bachelor of Science in Criminology</option>
                        </select>
                    </div>

                    <div>
                        <label for="year_level" class="block text-sm font-medium text-gray-700">Year Level</label>
                        <select id="year_level" name="year_level" required 
                            class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                            <option value="">Select your year level</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" id="password" name="password" required 
                            class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required 
                            class="mt-1 block w-full rounded-md border border-gray-300 py-2 px-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Register
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="login-page.php" class="font-medium text-indigo-600 hover:text-indigo-500">
                            Sign in
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;

            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
            }

            if (password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long!');
            }
        });
    </script>
</body>
</html> 