<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pollify Admin - Voting System Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h1 class="text-center text-3xl font-bold text-indigo-600">Pollify</h1>
            <h2 class="mt-3 text-center text-2xl font-bold tracking-tight text-gray-900">Admin Portal</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Manage polls, students, and voting system settings
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <!-- Admin Login Form -->
                <form id="adminLoginForm" class="space-y-6" action="admin_login_process.php" method="POST">
                    <div>
                        <label for="admin_id" class="block text-sm font-medium text-gray-700">Admin ID</label>
                        <div class="mt-1">
                            <input id="admin_id" name="admin_id" type="text" required 
                                class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" required 
                                class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember_me" type="checkbox" 
                                class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Reset password</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                            Sign in to Admin Portal
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="bg-white px-2 text-gray-500">Need help?</span>
                        </div>
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">Contact the system administrator</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Security Notice -->
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center text-xs text-gray-500">
                <p>This is a secure admin portal. Unauthorized access attempts will be logged and reported.</p>
            </div>
        </div>
    </div>

    <script>
        // Add basic form validation
        document.getElementById('adminLoginForm').addEventListener('submit', function(e) {
            const adminId = document.getElementById('admin_id').value;
            const password = document.getElementById('password').value;

            if (!adminId || !password) {
                e.preventDefault();
                alert('Please fill in all required fields');
                return;
            }

            // Additional validation can be added here
        });

        // Check for HTTPS
        if (window.location.protocol !== 'https:') {
            console.warn('Warning: This page should be accessed via HTTPS for security.');
        }
    </script>
</body>
</html>