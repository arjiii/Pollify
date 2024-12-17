<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pollify - Student Voting Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-2xl font-bold text-indigo-600">Pollify</h1>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-gray-700 mr-4">Welcome, John Doe</span>
                        <button onclick="logout()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Logout
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Voting Status Summary -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Active Polls</h3>
                    <p class="mt-1 text-3xl font-semibold text-indigo-600">3</p>
                    <p class="text-sm text-gray-500">Polls requiring your vote</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Your Participation</h3>
                    <p class="mt-1 text-3xl font-semibold text-green-600">85%</p>
                    <p class="text-sm text-gray-500">Overall voting rate</p>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Next Closing</h3>
                    <p class="mt-1 text-3xl font-semibold text-orange-600">2d 4h</p>
                    <p class="text-sm text-gray-500">Until poll closes</p>
                </div>
            </div>
        </div>

        <!-- Active Polls Section -->
        <div class="space-y-6 mb-8">
            <h2 class="text-xl font-bold text-gray-900">Active Polls</h2>
            
            <!-- Poll Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Student Council Election 2024</h3>
                            <p class="mt-1 text-sm text-gray-500">Choose your student council representatives for the upcoming academic year.</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            Open
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Closes in: 2 days 4 hours</span>
                            <span>Total Votes: 1,234</span>
                        </div>
                        <div class="mt-4">
                            <button onclick="location.href='vote.php?id=1'" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Cast Your Vote
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Another Poll Card -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Campus Facilities Survey</h3>
                            <p class="mt-1 text-sm text-gray-500">Share your feedback on campus facilities and suggest improvements.</p>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                            Closing Soon
                        </span>
                    </div>
                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Closes in: 6 hours</span>
                            <span>Total Responses: 856</span>
                        </div>
                        <div class="mt-4">
                            <button onclick="location.href='vote.php?id=2'" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Take Survey
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Past Polls & Results -->
        <div class="space-y-6">
            <h2 class="text-xl font-bold text-gray-900">Recent Results</h2>
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <ul class="divide-y divide-gray-200">
                    <li>
                        <a href="results.php?id=1" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            Sports Day Events Selection
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Ended on March 15, 2024
                                        </p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            Completed
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="results.php?id=2" class="block hover:bg-gray-50">
                            <div class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-indigo-600 truncate">
                                            Cafeteria Menu Voting
                                        </p>
                                        <p class="mt-1 text-sm text-gray-500">
                                            Ended on March 10, 2024
                                        </p>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            Completed
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Profile/Settings Modal -->
    <div id="profileModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg px-4 pt-5 pb-4 overflow-hidden shadow-xl transform transition-all sm:max-w-lg sm:w-full">
                <!-- Profile settings content here -->
            </div>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = 'logout.php';
        }

        // Additional functions for interactivity can be added here
        function showProfile() {
            document.getElementById('profileModal').classList.remove('hidden');
        }

        function hideProfile() {
            document.getElementById('profileModal').classList.add('hidden');
        }
    </script>
</body>
</html>