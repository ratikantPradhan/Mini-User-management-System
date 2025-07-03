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
$error = array();

if (isset($_POST['submit'])) {
    // Input validation
    $id = $_POST['id'];
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    // Check if username is valid
    if (empty($username) || strlen($username) < 3 || strlen($username) > 16) {
        $error[] = "Username must be between 3 and 16 characters.";
    }

    // Check if email is valid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Invalid email address.";
    }

    // Check if password is valid
    if (empty($password) || strlen($password) < 8) {
        $error[] = "Password must be at least 8 characters.";
    }

    if (empty($password_confirm) || strlen($password_confirm) < 8) {
        $error[] = "Password must be at least 8 characters.";
    }
    // Check if confirm password is valid
    if (empty($password) || $password !== $password_confirm) {
        $error[] = "Passwords do not match.";
    }

    // If no errors, insert data into database
    if (empty($error)) {
        $password = password_hash($password_confirm, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET password=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $password, $id);
        if (!$stmt->execute()) {
            $error[] = "Error updating user data: " . $conn->error;
        } else {
            $success = "Record Updated successfully";
            header("location: login.php");
            exit;
        }
    }
}
if (!empty($error)) {
    error_log("Error updating user data: " . implode(", ", $error));
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Update password page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form action="change_password.php" method="post" enctype="multipart/form-data">
        <div class="bg-white p-8 rounded shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6">Update Password</h2>
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
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Confirm Password</label>
                <input type="password" name="password_confirm" class="w-full border rounded px-3 py-2" required>
            </div>
            <input type="hidden" name="id" value="<?php echo $user_id; ?>">
            <button type="submit" name="submit" class="w-full bg-blue-500 text-white py-2 rounded">Update</button>

        </div>
    </form>
</body>

</html>