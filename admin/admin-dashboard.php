<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pollify Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.7.0/chart.min.js"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Navigation Bar -->
    <nav class="bg-indigo-600">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-white text-xl font-bold">Pollify Admin</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-white mr-4">Admin Name</span>
                    <button onclick="logout()" class="bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-800">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar Navigation -->
        <div class="w-64 bg-white h-screen shadow-lg">
            <nav class="mt-5 px-2">
                <a href="#" class="group flex items-center px-2 py-2 text-base leading-6 font-medium text-indigo-600 rounded-md bg-gray-100">
                    Dashboard Overview
                </a>
                <a href="#" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium text-gray-600 rounded-md hover:text-indigo-600 hover:bg-gray-100">
                    Manage Polls
                </a>
                <a href="#" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium text-gray-600 rounded-md hover:text-indigo-600 hover:bg-gray-100">
                    Student Management
                </a>
                <a href="#" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium text-gray-600 rounded-md hover:text-indigo-600 hover:bg-gray-100">
                    Results & Analytics
                </a>
                <a href="#" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium text-gray-600 rounded-md hover:text-indigo-600 hover:bg-gray-100">
                    System Settings
                </a>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 p-10">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-medium">Active Polls</h3>
                    <p class="text-3xl font-bold text-gray-900">8</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-medium">Total Students</h3>
                    <p class="text-3xl font-bold text-gray-900">2,547</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-medium">Today's Votes</h3>
                    <p class="text-3xl font-bold text-gray-900">156</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm font-medium">Pending Approvals</h3>
                    <p class="text-3xl font-bold text-gray-900">23</p>
                </div>
            </div>

            <!-- Active Polls Table -->
            <div class="bg-white shadow rounded-lg mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-medium text-gray-900">Active Polls</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poll Title</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Votes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Student Council Election</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-03-20</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-03-27</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1,234</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                    <button class="text-red-600 hover:text-red-900">End</button>
                                </td>
                            </tr>
                            <!-- Add more rows as needed -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Quick Actions and Recent Activity -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Quick Actions</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <button onclick="createNewPoll()" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Create New Poll
                            </button>
                            <button onclick="manageStudents()" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Manage Students
                            </button>
                            <button onclick="viewResults()" class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                View Results
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-medium text-gray-900">Recent Activity</h2>
                    </div>
                    <div class="p-6">
                        <ul class="space-y-4">
                            <li class="flex space-x-3">
                                <div class="flex-1 space-y-1">
                                    <p class="text-sm text-gray-600">New poll created: Class Representative Election</p>
                                    <p class="text-xs text-gray-500">2 hours ago</p>
                                </div>
                            </li>
                            <li class="flex space-x-3">
                                <div class="flex-1 space-y-1">
                                    <p class="text-sm text-gray-600">15 new students registered</p>
                                    <p class="text-xs text-gray-500">5 hours ago</p>
                                </div>
                            </li>
                            <li class="flex space-x-3">
                                <div class="flex-1 space-y-1">
                                    <p class="text-sm text-gray-600">Sports Captain Election ended</p>
                                    <p class="text-xs text-gray-500">1 day ago</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Placeholder functions for actions
        function logout() {
            window.location.href = 'admin_logout.php';
        }

        function createNewPoll() {
            // Implementation for creating new poll
            window.location.href = 'create_poll.php';
        }

        function manageStudents() {
            // Implementation for student management
            window.location.href = 'manage_students.php';
        }

        function viewResults() {
            // Implementation for viewing results
            window.location.href = 'view_results.php';
        }

        // Add more interactive features as needed
    </script>
</body>
</html>