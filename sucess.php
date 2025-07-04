<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sucess</title>
    <!-- tailwing -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

</head>

<body>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 px-3">
        <div class="w-full max-w-lg p-6 text-center bg-white rounded-xl shadow-lg hover:shadow-xl transition-all">

            <!-- Success Icon -->
            <div class="flex items-center justify-center w-20 h-20 mx-auto mb-5 bg-green-100 rounded-full">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <!-- Title -->
            <h1 class="mb-3 text-2xl font-bold text-green-600">
                Successful!
            </h1>

            <!-- Message -->
            <p class="mb-5 text-base text-gray-700">
                You can now log in and explore your dashboard. Redirecting you to the login page in a few seconds...
            </p>

            <!-- Info Box (Optional) -->
            <!-- <div class="p-4 mb-5 rounded-lg bg-blue-50 text-blue-700 text-sm">
                If you didn’t receive a confirmation email, check your spam folder.
            </div> -->

            <!-- Support Contact -->
            <p class="text-sm text-gray-600">
                Need help?
                <a href="mailto:support@example.com" class="text-blue-500 hover:underline">support@example.com</a>
            </p>

            <!-- Action Button -->
            <div class="mt-6">
                <a href="login.php"
                    class="inline-block px-6 py-3 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-200">
                    Go to Login
                </a>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            window.location.href = "login.php";
        }, 4000);
    </script>
</body>

</html>