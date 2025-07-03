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
$role = $_SESSION['role'];

// Get all users from database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- font awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* external css: flickity.css */

        * {
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        body {
            font-family: sans-serif;
        }

        .gallery {
            background: #EEE;
        }

        .gallery-cell {
            width: 66%;
            height: 200px;
            margin-right: 10px;
            background: #8C8;
            counter-increment: gallery-cell;
        }

        /* cell number */
        .gallery-cell:before {
            display: block;
            text-align: center;
            content: counter(gallery-cell);
            line-height: 200px;
            font-size: 80px;
            color: white;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
</head>

<body class="bg-gray-100 min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white shadow-md">
        <div class="p-6" style="position: sticky; top: 0; ">
            <h2 class="text-xl font-bold text-gray-700">
                <!--dashboard icon  -->
                <i class="fa fa-tachometer-alt"></i>
                Dashboard
            </h2>
        </div>
        <nav class="px-4 space-y-1" style="position: fixed; ">
            <a href="#overview" class="block py-2 px-4 text-gray-700 hover:bg-gray-200  rounded">Overview</a>
            <!-- <a href="#charts" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 rounded">Charts</a> -->
            <a href="#staff" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 rounded">All users</a>
            <a href="#carousel" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 rounded">Carousel</a>
            <a href="#services" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 rounded">Services</a>
            <a href="#blog" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 rounded">Blog</a>
            <a href="#faq" class="block py-2 px-4 text-gray-700 hover:bg-gray-200 rounded">FAQ</a>
            <a href="logout.php" class="block py-2 px-4 text-red-600 hover:bg-red-100 rounded">Logout</a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-6 space-y-8 overflow-y-auto">

        <!-- Header -->
        <header class="mb-4" id="overview">
            <h1 class="text-3xl font-bold text-gray-800">
                Welcome, <?php echo htmlspecialchars(strtoupper($role)); ?>
            </h1>
            <p class="text-gray-600">Here’s your dashboard overview.</p>
        </header>

        <!-- Stats Cards -->
        <section id="overview" class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-600">All users</p>
                <p class="text-2xl font-bold text-blue-600"><?php $sql = "SELECT * FROM users";
                                                            $result = $conn->query($sql);
                                                            echo $result->num_rows; ?></p>
            </div>
            <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-600">Active Today</p>
                <p class="text-2xl font-bold text-green-600">
                    <?php
                    $sql = "SELECT * FROM users";
                    $result = $conn->query($sql);
                    $users = $result->fetch_all(MYSQLI_ASSOC);
                    $activeCount = 0; // 
                    foreach ($users as $user) {
                        if ($user['status'] == 0) {
                            $activeCount++;
                        }
                    }
                    echo $activeCount;
                    ?>
                </p>

            </div>
            <!-- <div class="bg-white shadow rounded-lg p-6">
                <p class="text-gray-600">Pending Requests</p>
                <p class="text-2xl font-bold text-yellow-600">3</p>
            </div> -->
        </section>

        <!-- Charts -->


        <!-- Staff Table -->
        <section id="staff" class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-700 font-semibold mb-4">All Users</p>
            <div class="overflow-x-auto">
                <table class="min-w-full border divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50 text-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Sl no.</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Email</th>
                            <th class="px-4 py-2 text-left">Profile</th>
                            <th class="px-4 py-2 text-center" colspan="2">Action</th>
                            <th class="px-4 py-2 text-left">Report</th>

                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-800 p-2 m-2">
                        <?php
                        $sql = 'SELECT * FROM users WHERE status = 0';
                        $result = $conn->query($sql);

                        while ($user = $result->fetch_assoc()) { ?>
                            <tr>
                                <td class="px-4 py-2 text-left"><?php echo $user['id']; ?></td>
                                <td class="px-4 py-2 text-left"><?php echo $user['username']; ?></td>
                                <td class="px-4 py-2 text-left"> <?php echo $user['email']; ?></td>
                                <td class="px-4 py-2 text-left"> <img src="<?php echo $user['profile']; ?>" alt="" class="flex-shrink-0 w-10 h-10 rounded-full display-flex float-left"></td>
                                <td class="px-1 py-2 text-right">
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"><a href="edit_user.php?id=<?= $user['id'] ?>" class="text-white hover:text-white-600 hover:underline">Edit</button>

                                </td>
                                <td class="px-1 py-2 text-left">
                                    <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteUser(<?= $user['id'] ?>)">
                                        Delete
                                    </button>
                                </td>
                                <td class="px-1 py-2 text-left"><button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"><a href="report.php?id=<?= $user['id'] ?>" class="text-white hover:text-white-600 hover:underline">Download</button></td>
                            </tr>
                        <?php } ?>

                    </tbody>
                </table>
            </div>
        </section>
        <section id="carousel" class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-700 font-semibold mb-4">Carousel</p>


            <div id="controls-carousel" class="relative w-full" data-carousel="static">
                <!-- Carousel wrapper -->
                <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                    <!-- Item 1 -->
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="uploads/ball.jpg" class="absolute block h-full w-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 " alt="...">
                    </div>
                    <!-- Item 2 -->
                    <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                        <img src="uploads/images.jpg" class="absolute block h-full w-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                    </div>
                    <!-- Item 3 -->
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="uploads/keyboard.jpg" class="absolute block h-full w-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                    </div>
                    <!-- Item 4 -->
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="uploads/mouse.jpg" class="absolute block h-full w-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                    </div>
                    <!-- Item 5 -->
                    <div class="hidden duration-700 ease-in-out" data-carousel-item>
                        <img src="uploads/helmet.jpg" class=" absolute block h-full w-auto -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                    </div>
                </div>
                <!-- Slider controls -->
                <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4" />
                        </svg>
                        <span class="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                        <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="sr-only">Next</span>
                    </span>
                </button>
            </div>





        </section>
        <section id="services" class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-700 font-semibold mb-4">Services</p>
            <div class="max-w-4xl mx-auto px-5 mt-16">

                <div class="text-center">
                    <h2 class="font-semibold text-3xl">Features you'll love</h2>
                    <p class="max-w-md mx-auto mt-2 text-gray-500">A responsive documentation template built for everyone who wants
                        to create a plugin.</p>
                </div>


                <div class="grid md:grid-cols-2 gap-10 mt-10">


                    <div class="flex gap-4 items-start">
                        <span class="text-violet-600 bg-violet-500/10 p-3 rounded-full">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M0.849976 1.74998C0.849976 1.25292 1.25292 0.849976 1.74998 0.849976H3.24998C3.74703 0.849976 4.14998 1.25292 4.14998 1.74998V2.04998H10.85V1.74998C10.85 1.25292 11.2529 0.849976 11.75 0.849976H13.25C13.747 0.849976 14.15 1.25292 14.15 1.74998V3.24998C14.15 3.74703 13.747 4.14998 13.25 4.14998H12.95V10.85H13.25C13.747 10.85 14.15 11.2529 14.15 11.75V13.25C14.15 13.747 13.747 14.15 13.25 14.15H11.75C11.2529 14.15 10.85 13.747 10.85 13.25V12.95H4.14998V13.25C4.14998 13.747 3.74703 14.15 3.24998 14.15H1.74998C1.25292 14.15 0.849976 13.747 0.849976 13.25V11.75C0.849976 11.2529 1.25292 10.85 1.74998 10.85H2.04998V4.14998H1.74998C1.25292 4.14998 0.849976 3.74703 0.849976 3.24998V1.74998ZM2.94998 4.14998V10.85H3.24998C3.74703 10.85 4.14998 11.2529 4.14998 11.75V12.05H10.85V11.75C10.85 11.2529 11.2529 10.85 11.75 10.85H12.05V4.14998H11.75C11.2529 4.14998 10.85 3.74703 10.85 3.24998V2.94998H4.14998V3.24998C4.14998 3.74703 3.74703 4.14998 3.24998 4.14998H2.94998ZM2.34998 1.74998H1.74998V2.34998V2.64998V3.24998H2.34998H2.64998H3.24998V2.64998V2.34998V1.74998H2.64998H2.34998ZM5.09998 5.99998C5.09998 5.50292 5.50292 5.09998 5.99998 5.09998H6.99998C7.49703 5.09998 7.89998 5.50292 7.89998 5.99998V6.99998C7.89998 7.03591 7.89787 7.07134 7.89378 7.10618C7.92861 7.10208 7.96405 7.09998 7.99998 7.09998H8.99998C9.49703 7.09998 9.89998 7.50292 9.89998 7.99998V8.99998C9.89998 9.49703 9.49703 9.89998 8.99998 9.89998H7.99998C7.50292 9.89998 7.09998 9.49703 7.09998 8.99998V7.99998C7.09998 7.96405 7.10208 7.92861 7.10618 7.89378C7.07134 7.89787 7.03591 7.89998 6.99998 7.89998H5.99998C5.50292 7.89998 5.09998 7.49703 5.09998 6.99998V5.99998ZM6.09998 5.99998H5.99998V6.09998V6.89998V6.99998H6.09998H6.89998H6.99998V6.89998V6.09998V5.99998H6.89998H6.09998ZM7.99998 7.99998H8.09998H8.89998H8.99998V8.09998V8.89998V8.99998H8.89998H8.09998H7.99998V8.89998V8.09998V7.99998ZM2.64998 11.75H2.34998H1.74998V12.35V12.65V13.25H2.34998H2.64998H3.24998V12.65V12.35V11.75H2.64998ZM11.75 1.74998H12.35H12.65H13.25V2.34998V2.64998V3.24998H12.65H12.35H11.75V2.64998V2.34998V1.74998ZM12.65 11.75H12.35H11.75V12.35V12.65V13.25H12.35H12.65H13.25V12.65V12.35V11.75H12.65Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg></span>
                        <div>
                            <h3 class="font-semibold text-xl">User Authentication</h3>
                            <p class="mt-1 text-gray-500"> You don't need to be an expert to customize this plugin. Our code is very
                                readable and well documented.</p>
                        </div>
                    </div>


                    <div class="flex gap-4 items-start">
                        <span class="text-violet-600 bg-violet-500/10 p-3 rounded-full">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M3.00014 2.73895C3.00014 2.94698 2.76087 3.06401 2.59666 2.93628L1.00386 1.69744C0.875177 1.59735 0.875177 1.40286 1.00386 1.30277L2.59666 0.063928C2.76087 -0.0637944 3.00014 0.0532293 3.00014 0.261266V1.00012H9.00009V0.261296C9.00009 0.0532591 9.23936 -0.0637646 9.40358 0.0639578L10.9964 1.3028C11.1251 1.40289 11.1251 1.59738 10.9964 1.69747L9.40358 2.93631C9.23936 3.06404 9.00009 2.94701 9.00009 2.73898V2.00012H3.00014V2.73895ZM9.50002 4.99998H2.50002C2.22388 4.99998 2.00002 5.22384 2.00002 5.49998V12.5C2.00002 12.7761 2.22388 13 2.50002 13H9.50002C9.77616 13 10 12.7761 10 12.5V5.49998C10 5.22384 9.77616 4.99998 9.50002 4.99998ZM2.50002 3.99998C1.67159 3.99998 1.00002 4.67156 1.00002 5.49998V12.5C1.00002 13.3284 1.67159 14 2.50002 14H9.50002C10.3284 14 11 13.3284 11 12.5V5.49998C11 4.67156 10.3284 3.99998 9.50002 3.99998H2.50002ZM14.7389 6.00001H14V12H14.7389C14.9469 12 15.064 12.2393 14.9362 12.4035L13.6974 13.9963C13.5973 14.125 13.4028 14.125 13.3027 13.9963L12.0639 12.4035C11.9362 12.2393 12.0532 12 12.2612 12H13V6.00001H12.2612C12.0532 6.00001 11.9361 5.76074 12.0639 5.59653L13.3027 4.00373C13.4028 3.87505 13.5973 3.87505 13.6974 4.00374L14.9362 5.59653C15.0639 5.76074 14.9469 6.00001 14.7389 6.00001Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg></span>
                        <div>
                            <h3 class="font-semibold text-xl">Fully Responsive</h3>
                            <p class="mt-1 text-gray-500"> With mobile, tablet &amp; desktop support it doesn't matter what device
                                you're using. This plugin is responsive in all browsers. </p>
                        </div>
                    </div>


                    <div class="flex gap-4 items-start">
                        <span class="text-violet-600 bg-violet-500/10 p-3 rounded-full">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M1 2C0.447715 2 0 2.44772 0 3V12C0 12.5523 0.447715 13 1 13H14C14.5523 13 15 12.5523 15 12V3C15 2.44772 14.5523 2 14 2H1ZM1 3L14 3V3.92494C13.9174 3.92486 13.8338 3.94751 13.7589 3.99505L7.5 7.96703L1.24112 3.99505C1.16621 3.94751 1.0826 3.92486 1 3.92494V3ZM1 4.90797V12H14V4.90797L7.74112 8.87995C7.59394 8.97335 7.40606 8.97335 7.25888 8.87995L1 4.90797Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg></span>
                        <div>
                            <h3 class="font-semibold text-xl">Role Based Dashboard</h3>
                            <p class="mt-1 text-gray-500"> Our plugins are supported by sponsors who provide community support.
                                Sponsors will get email support on weekdays.</p>
                        </div>
                    </div>


                    <div class="flex gap-4 items-start">
                        <span class="text-violet-600 bg-violet-500/10 p-3 rounded-full">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M4.5 2C3.11929 2 2 3.11929 2 4.5C2 5.88072 3.11929 7 4.5 7C5.88072 7 7 5.88072 7 4.5C7 3.11929 5.88072 2 4.5 2ZM3 4.5C3 3.67157 3.67157 3 4.5 3C5.32843 3 6 3.67157 6 4.5C6 5.32843 5.32843 6 4.5 6C3.67157 6 3 5.32843 3 4.5ZM10.5 2C9.11929 2 8 3.11929 8 4.5C8 5.88072 9.11929 7 10.5 7C11.8807 7 13 5.88072 13 4.5C13 3.11929 11.8807 2 10.5 2ZM9 4.5C9 3.67157 9.67157 3 10.5 3C11.3284 3 12 3.67157 12 4.5C12 5.32843 11.3284 6 10.5 6C9.67157 6 9 5.32843 9 4.5ZM2 10.5C2 9.11929 3.11929 8 4.5 8C5.88072 8 7 9.11929 7 10.5C7 11.8807 5.88072 13 4.5 13C3.11929 13 2 11.8807 2 10.5ZM4.5 9C3.67157 9 3 9.67157 3 10.5C3 11.3284 3.67157 12 4.5 12C5.32843 12 6 11.3284 6 10.5C6 9.67157 5.32843 9 4.5 9ZM10.5 8C9.11929 8 8 9.11929 8 10.5C8 11.8807 9.11929 13 10.5 13C11.8807 13 13 11.8807 13 10.5C13 9.11929 11.8807 8 10.5 8ZM9 10.5C9 9.67157 9.67157 9 10.5 9C11.3284 9 12 9.67157 12 10.5C12 11.3284 11.3284 12 10.5 12C9.67157 12 9 11.3284 9 10.5Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg></span>
                        <div>
                            <h3 class="font-semibold text-xl">Usser Report Generation</h3>
                            <p class="mt-1 text-gray-500"> We make sure our plugins are working perfectly with all modern browsers
                                available such as Chrome, Firefox, Safari. </p>
                        </div>
                    </div>


                    <!-- <div class="flex gap-4 items-start">
                        <span class="text-violet-600 bg-violet-500/10 p-3 rounded-full">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M9.96424 2.68571C10.0668 2.42931 9.94209 2.13833 9.6857 2.03577C9.4293 1.93322 9.13832 2.05792 9.03576 2.31432L5.03576 12.3143C4.9332 12.5707 5.05791 12.8617 5.3143 12.9642C5.5707 13.0668 5.86168 12.9421 5.96424 12.6857L9.96424 2.68571ZM3.85355 5.14646C4.04882 5.34172 4.04882 5.6583 3.85355 5.85356L2.20711 7.50001L3.85355 9.14646C4.04882 9.34172 4.04882 9.6583 3.85355 9.85356C3.65829 10.0488 3.34171 10.0488 3.14645 9.85356L1.14645 7.85356C0.951184 7.6583 0.951184 7.34172 1.14645 7.14646L3.14645 5.14646C3.34171 4.9512 3.65829 4.9512 3.85355 5.14646ZM11.1464 5.14646C11.3417 4.9512 11.6583 4.9512 11.8536 5.14646L13.8536 7.14646C14.0488 7.34172 14.0488 7.6583 13.8536 7.85356L11.8536 9.85356C11.6583 10.0488 11.3417 10.0488 11.1464 9.85356C10.9512 9.6583 10.9512 9.34172 11.1464 9.14646L12.7929 7.50001L11.1464 5.85356C10.9512 5.6583 10.9512 5.34172 11.1464 5.14646Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg></span>
                        <div>
                            <h3 class="font-semibold text-xl">Multiple Devices Support</h3>
                            <p class="mt-1 text-gray-500"> We strictly follow a set of guidelines along with unit tests to make sure
                                your implementation as easy as possible. </p>
                        </div>
                    </div> -->


                    <!-- <div class="flex gap-4 items-start">
                        <span class="text-violet-600 bg-violet-500/10 p-3 rounded-full">
                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5">
                                <path d="M7.28856 0.796908C7.42258 0.734364 7.57742 0.734364 7.71144 0.796908L13.7114 3.59691C13.8875 3.67906 14 3.85574 14 4.05V10.95C14 11.1443 13.8875 11.3209 13.7114 11.4031L7.71144 14.2031C7.57742 14.2656 7.42258 14.2656 7.28856 14.2031L1.28856 11.4031C1.11252 11.3209 1 11.1443 1 10.95V4.05C1 3.85574 1.11252 3.67906 1.28856 3.59691L7.28856 0.796908ZM2 4.80578L7 6.93078V12.9649L2 10.6316V4.80578ZM8 12.9649L13 10.6316V4.80578L8 6.93078V12.9649ZM7.5 6.05672L12.2719 4.02866L7.5 1.80176L2.72809 4.02866L7.5 6.05672Z" fill="currentColor" fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg></span>
                        <div>
                            <h3 class="font-semibold text-xl">Frequent Updates and Support</h3>
                            <p class="mt-1 text-gray-500"> This templatte is actively maintained by the core plugin team. We are
                                working on fixing each of the issues reported.</p>
                        </div>
                    </div> -->

                </div>
            </div>
        </section>
        <section id="blog" class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-700 font-semibold mb-4">Blog</p>
            <section class="bg-white dark:bg-gray-900">
                <!-- Title Section -->
                <div class="text-center py-10">
                    <h1 class="text-4xl font-bold text-black dark:text-white mb-4">Discover New Auth0 Features</h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">Rely on the Auth0 identity platform to add sophisticated authentication and authorization to your applications. Centralize and manage users from multiple identity providers and give them branded, seamless signup and login experiences. Finely control access with a degree of customization that can accommodate even the most complex security requirements.</p>
                </div>

                <!-- Content Section -->
                <div class="px-8 py-10 mx-auto lg:max-w-screen-xl sm:max-w-xl md:max-w-full sm:px-12 md:px-16 lg:py-20 sm:py-16">
                    <div class="grid gap-x-8 gap-y-12 sm:gap-y-16 md:grid-cols-2 lg:grid-cols-3">
                        <div class="relative">
                            <a href="#_" class="block overflow-hidden group rounded-xl shadow-lg">
                                <img src="https://images.ctfassets.net/23aumh6u8s0i/1oXn0tISn2hQ6z3Ob55O75/5b51a72ca589febc59777cd820518091/GenAi_hero_image.jpg" class="object-cover w-full h-56 transition-all duration-300 ease-out sm:h-64 group-hover:scale-110" alt="Adventure">
                            </a>
                            <div class="relative mt-5">
                                <p class="uppercase font-semibold text-xs mb-2.5 text-purple-600">September 10th 2023</p>
                                <a href="" class="block mb-3 hover:underline">
                                    <h2 class="text-2xl font-bold leading-5 text-black dark:text-white transition-colors duration-200 hover:text-purple-700 dark:hover:text-purple-400">
                                        Secure Your APIs with Speed!

                                    </h2>
                                </a>
                                <p class="mb-4 text-gray-700 dark:text-gray-300">The official Auth0 MCP Server is available now. We’ll go over how you can use Claude Desktop, Cursor, Windsurf and other MCP Clients to securely manage your Auth0 tenant using natural language. Learn more with our Get Started guide.
                                </p>
                                <a href="#_" class="font-medium underline text-purple-600 dark:text-purple-400">Read More</a>
                            </div>
                        </div>

                        <div class="relative">
                            <a href="#_" class="block overflow-hidden group rounded-xl shadow-lg">
                                <img src="https://images.ctfassets.net/23aumh6u8s0i/5eRmaqCygvldjzeJopYpeG/c690a573834b90c39fde767d547a6903/hero-MCP-Spec-Updates-June-18.png" class="object-cover w-full h-56 transition-all duration-300 ease-out sm:h-64 group-hover:scale-110" alt="Ocean">
                            </a>
                            <div class="relative mt-5">
                                <p class="uppercase font-semibold text-xs mb-2.5 text-purple-600">September 15th 2023</p>
                                <a href="#" class="block mb-3 hover:underline">
                                    <h2 class="text-2xl font-bold leading-5 text-black dark:text-white transition-colors duration-200 hover:text-purple-700 dark:hover:text-purple-400">
                                        Auth0 MCP Server
                                    </h2>
                                </a>
                                <p class="mb-4 text-gray-700 dark:text-gray-300">The official Auth0 MCP Server is available now. We’ll go over how you can use Claude Desktop, Cursor, Windsurf and other MCP Clients to securely manage your Auth0 tenant using natural language. Learn more with our Get Started guide..</p>
                                <a href="#_" class="font-medium underline text-purple-600 dark:text-purple-400">Read More</a>
                            </div>
                        </div>

                        <div class="relative">
                            <a href="#_" class="block overflow-hidden group rounded-xl shadow-lg">
                                <img src="https://images.ctfassets.net/23aumh6u8s0i/4uUz1HbI1lBMrnzUN5MLG5/83948c2d6402d36d1541284c46054306/auth0-aws-hero.png" class="object-cover w-full h-56 transition-all duration-300 ease-out sm:h-64 group-hover:scale-110" alt="Desert Adventure">
                            </a>
                            <div class="relative mt-5">
                                <p class="uppercase font-semibold text-xs mb-2.5 text-purple-600">October 5th 2023</p>
                                <a href="#" class="block mb-3 hover:underline">
                                    <h2 class="text-2xl font-bold leading-5 text-black dark:text-white transition-colors duration-200 hover:text-purple-700 dark:hover:text-purple-400">
                                        Auth0 and Amazon Web Services:
                                    </h2>
                                </a>
                                <p class="mb-4 text-gray-700 dark:text-gray-300">The Secret Ingredients That Drive Fan The one aspect of your tech stack that can transform your fan experiences for the better.Engagement Learn how Auth0 and Amazon Web Services can enhance fan engagement for sports organizations.</p>
                                <a href="#_" class="font-medium underline text-purple-600 dark:text-purple-400">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </section>
        <section id="faq" class="bg-white shadow rounded-lg p-6">
            <p class="text-gray-700 font-semibold mb-4">FAQ</p>
            <section class="bg-white text-black-700 py-3 min-h-screen">
                <div class="container flex flex-col justify-center p-4 mx-auto md:p-8">
                    <h2 class="mb-12 text-4xl font-bold leadi text-center sm:text-5xl">Frequently Asked Questions</h2>
                    <div class="flex flex-col divide-y sm:px-8 lg:px-12 xl:px-32 divide-gray-700">
                        <details>
                            <summary class="py-2 outline-none cursor-pointer focus:underline">What is a Google Cloud organization?</summary>
                            <div class="px-4 pb-4">
                                <p>A Google Cloud organization is a container for Google Cloud projects (including Firebase projects). This hierarchy enables better organization, access management, and auditing of your Google Cloud and Firebase projects. For more information, refer to Creating and Managing Organizations..</p>
                            </div>
                        </details>
                        <details>
                            <summary class="py-2 outline-none cursor-pointer focus:underline">Why does my Google Cloud project have a label of firebase:enabled?</summary>
                            <div class="px-4 pb-4">
                                <p>Be aware that manually adding this label to your list of project labels does NOT enable Firebase-specific configurations and services for your Google Cloud project. To do that, you need to add Firebase using the Firebase console (or, for advanced use cases, using the Firebase Management REST API or the Firebase CLI).</p>
                            </div>
                        </details>
                        <details>
                            <summary class="py-2 outline-none cursor-pointer focus:underline">How many projects can I have per Google Account (email address)?</summary>
                            <div class="px-4 pb-4">
                                <p>Note the following about the limit on project-creation quota:

                                    This limit is not specific to Firebase. Firebase's limits on project quota are the same as those for Google Cloud.
                                    In the rare case that's it's needed, you can request an increase in project quota.
                                    The complete deletion of a project requires 30 days and counts toward project quota until the project is fully deleted.
                                    Learn about Firebase's recommended general best practices for setting up Firebase projects</p>
                            </div>
                        </details>
                        <details>
                            <summary class="py-2 outline-none cursor-pointer focus:underline">How do I assign a project member a role, like the Owner role?</summary>
                            <div class="px-4 pb-4">
                                <p>To manage the role(s) assigned to each project member, you must be an Owner of the Firebase project (or be assigned a role with the permission resourcemanager.projects.setIamPolicy).<a href="" class="underline">example@gmail.com</a> for assistance.</p>
                            </div>
                        </details>
                        <details>
                            <summary class="py-2 outline-none cursor-pointer focus:underline">Why or when should I assign a project member the Owner role? </summary>
                            <div class="px-4 pb-4">
                                <p>The email you received should contain a link to open your Firebase project. Clicking the link in the email should open the project in the Firebase console.

                                    If you're not able to open the project in the link, make sure that you're signed into Firebase using the same Google account that received the email about the project. You can sign in and out of the Firebase console via your account avatar in the top-right corner of the console.</p>
                            </div>
                        </details>
                        <details>
                            <summary class="py-2 outline-none cursor-pointer focus:underline">What is your customer support contact?</summary>
                            <div class="px-4 pb-4">
                                <p>If you have any questions, concerns, or need assistance, you can reach our customer support team at 9911083755 during our business hours, Monday to Saturday from 10 am to 6 pm. You can also contact us via email at <a href="" class="underline">example@gmail.com</a>.</p>
                            </div>
                        </details>
                        <details>
                            <summary class="py-2 outline-none cursor-pointer focus:underline">What are your terms and conditions?</summary>
                            <div class="px-4 pb-4">
                                <p>You can find our detailed terms and conditions by visiting our
                                    <a href="" class="underline">Terms of Service</a>
                                    page on our website. It includes information about our policies, user guidelines, and more.
                                </p>
                            </div>
                        </details>
                    </div>
                </div>
            </section>

        </section>


    </main>

    </div>
    <script>
        function deleteUser(id) {
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "delete_user.php?id=" + id;
            }
        }
    </script>
</body>

</html>