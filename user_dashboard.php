<?php
require "db.php";
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$email = $_SESSION['email'];

// Get user data from database
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Display user dashboard
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Include Tailwind CSS -->
   
</head>

<body>
    <div class="dashboard-container">
        <div class="profile-section">
            <img src="<?php echo $user_data['profile']; ?>" alt="Profile Picture" class="profile-img " height="60px">
            <h1>Welcome, <?php echo htmlspecialchars($user_data['username']); ?>!</h1>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user_data['email']); ?></p>

        </div>



        <div class="action-buttons">
            <a href="edit_user.php?id=<?= $_SESSION['user_id'] ?>" class="text-blue-600 hover:underline"><button>Edit</button></a>
            <a href="change_password.php?id=<?= $_SESSION['user_id'] ?>" class="btn"><button>Change Password</button></a>
            <a href="logout.php" class="btn logout-btn"><button>Logout</button></a>
           <a href="report.php?id=<?= $user_data['id'] ?>" ><button>Download Report</button></a>
        </div>

    </div>

</body>

</html>
<!-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-indigo-800 to-blue-900 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-4xl w-full p-8 transition-all duration-300 animate-fade-in">
        <div class="flex flex-col md:flex-row">
            <div class="md:w-1/3 text-center mb-8 md:mb-0">
                <img src="<?php echo $user_data['profile']; ?>" alt="Profile Picture" class="rounded-full w-48 h-48 mx-auto mb-4 border-4 border-indigo-800 dark:border-blue-900 transition-transform duration-300 hover:scale-105">
                <h1 class="text-2xl font-bold text-indigo-800 dark:text-white mb-2"><?php echo htmlspecialchars($user_data['username']); ?></h1>
                <p class="text-gray-600 dark:text-gray-300"><?php echo htmlspecialchars(strtoupper($user_data['role'])); ?></p>
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><a href="edit_user.php?id=<?= $_SESSION['user_id'] ?>" class="text-white hover:text-white-600 hover:underline">Edit Profile</a></button>
                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"><a href="report.php?id=<?= $user_data['id'] ?>" class="text-white hover:text-white-600 hover:underline">Download</button>
            </div>
            <div class="md:w-2/3 md:pl-8">
                <h2 class="text-xl font-semibold text-indigo-800 dark:text-white mb-4">About Me</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Passionate software developer with 5 years of experience in web technologies.
                    I love creating user-friendly applications and solving complex problems.
                </p>
                <h2 class="text-xl font-semibold text-indigo-800 dark:text-white mb-4">Skills</h2>
                <div class="flex flex-wrap gap-2 mb-6">
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">JavaScript</span>
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">React</span>
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">Node.js</span>
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">Python</span>
                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">SQL</span>
                </div>
                <h2 class="text-xl font-semibold text-indigo-800 dark:text-white mb-4">Contact Information</h2>
                <ul class="space-y-2 text-gray-700 dark:text-gray-300">
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-800 dark:text-blue-900" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                        </svg>
                        john.doe@example.com
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-800 dark:text-blue-900" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        +1 (555) 123-4567
                    </li>
                    <li class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-800 dark:text-blue-900" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
                        </svg>
                        San Francisco, CA
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
    </style>

    <script>
        // Toggle dark mode based on system preference
        if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.classList.add('dark');
        }

        // Add hover effect to skill tags
        const skillTags = document.querySelectorAll('.bg-indigo-100');
        skillTags.forEach(tag => {
            tag.addEventListener('mouseover', () => {
                tag.classList.remove('bg-indigo-100', 'text-indigo-800');
                tag.classList.add('bg-blue-900', 'text-white');
            });
            tag.addEventListener('mouseout', () => {
                tag.classList.remove('bg-blue-900', 'text-white');
                tag.classList.add('bg-indigo-100', 'text-indigo-800');
            });
        });
    </script>
</body>

</html> -->