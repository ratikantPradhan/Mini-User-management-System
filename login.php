<?php
require "db.php";
session_start();

$error = array();

if (isset($_POST['submit'])) {
    // Input validation
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Check if email is valid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Invalid email address.";
    }

    // Check if password is valid
    if (empty($password) || strlen($password) < 8) {
        $error[] = "Password must be at least 8 characters.";
    }

    // If no errors, check if user exists in database
    if (empty($error)) {
        $sql = "SELECT * FROM users WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Check if password is correct
            if (password_verify($password, $user['password'])) {
                // Set session variables
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['profile'] = $user['profile'];
                $_SESSION['role'] = $user['role'];

                // Redirect to dashboard based on user role
                if ($_SESSION['role'] == 'user') {
                    header("Location: user_dashboard.php");
                } elseif ($_SESSION['role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    $error[] = "Invalid user role.";
                }
                exit();
            } else {
                $error[] = "Incorrect password.";
            }
        } else {
            $error[] = "User not found.";
        }
    } else {
        $error[] = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form action="login.php" method="post" enctype="multipart/form-data" id="loginForm" novalidate>
        <div class=" bg-white p-8 rounded shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6">Login</h2>
            <?php if (!empty($error)) { ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul>
                        <?php foreach ($error as $err) { ?>
                            <li><?php echo $err; ?></li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            <?php if (isset($success)) { ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <?php echo $success; ?>
                </div>
            <?php } ?>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" title="Enter a valid email address(email@domain.com)" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" title="8–16 characters with atleast a uppercase, lowercase, number, and special character" required>
            </div>
            <!-- forget password -->


            <div class="mb-4 flex items-center justify-between">
                <label class="inline-flex items-center text-sm text-gray-700">
                    <input type="checkbox" name="remember" id="remember" class="form-checkbox text-blue-500">
                    <span class="ml-2">Remember me</span>
                </label>

                <a href="forget_password.php" class="text-blue-500 text-sm hover:underline">
                    Forgot password?
                </a>
            </div>

            <button type="submit" name="submit" class="w-full bg-blue-500 text-white py-2 rounded">Login</button>
            <p class="mt-4 text-center text-sm">Don't have an account? <a href="register.php" class="text-blue-500">Register</a></p>
        </div>
    </form>
    <script>
        const form = document.getElementById("loginForm");

        const fields = {
            email: {
                input: form.querySelector("[name='email']"),
                validate: value => /^\S+@\S+\.\S+$/.test(value),
                message: "Enter a valid email address.( email@domain.com)"
            
            },
            password: {
                input: form.querySelector("[name='password']"),
                validate: value => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?#&])[A-Za-z\d@$!%*?#&]{8,16}$/.test(value),
                message: "Password must be 8–16 chars with uppercase, lowercase, number, and special character."
            }
        };

        // Attach error display and validation to each input
        Object.values(fields).forEach(({
            input,
            validate,
            message
        }) => {
            const errorElement = document.createElement("div");
            errorElement.className = "text-red-500 text-sm mt-1";
            input.insertAdjacentElement("afterend", errorElement);

            input.addEventListener("input", () => {
                const value = input.type === "file" ? input.files[0] : input.value;
                if (!validate(value)) {
                    errorElement.textContent = message;
                    input.classList.add("border-red-500");
                    input.classList.remove("border-green-500");
                } else {
                    errorElement.textContent = "";
                    input.classList.remove("border-red-500");
                    input.classList.add("border-green-500");
                }
            });
        });

        // Prevent form submission on invalid input
        form.addEventListener("submit", (e) => {
            let isValid = true;
            Object.values(fields).forEach(({
                input,
                validate
            }) => {
                const value = input.type === "file" ? input.files[0] : input.value;
                if (!validate(value)) {
                    input.dispatchEvent(new Event("input"));
                    isValid = false;
                }
            });
            if (!isValid) e.preventDefault();
        });
    </script>
</body>

</html>