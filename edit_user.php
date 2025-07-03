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

// Check if id parameter is set
if (!isset($_GET['id'])) {
    $error[] = "ID parameter is not set.";
}

// Fetch user data
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT username, email, profile FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Check if user data is found
    if (!$user) {
        $error[] = "User data not found.";
    }
}

$error = array();

if (isset($_POST['submit'])) {
    // Input validation
    $id= $_POST['id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);

    // Check if username is valid
    if (empty($username) || strlen($username) < 3 || strlen($username) > 16) {
        $error[] = "Username must be between 3 and 16 characters.";
    }

    // Check if email is valid
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error[] = "Invalid email address.";
    }

    // File upload handling
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $profile = $target_file;

    // Check if file was uploaded
    if (empty($_FILES["fileToUpload"]["name"])) {
        $error[] = "Please select a file to upload.";
    }

    // Check if file is an image
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $error[] = "Only JPG, JPEG, PNG, and GIF files are allowed.";
    }

    // Check if file size is too large
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        $error[] = "Sorry, your file is too large.";
    }

    // If no errors, insert data into database
    if (empty($error)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE users SET username=?, email=?, profile=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $email, $profile, $id);

        if (!$stmt->execute()) {
            $error[] = "Error updating user data: " . $conn->error;
        } else {
            // Upload file
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                $success = "Record Updated successfully";
                if ($_SESSION['role'] == 'user') {
                    header("Location: user_dashboard.php");
                } elseif ($_SESSION['role'] == 'admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    $error[] = "Invalid user role.";
                }
                exit();
            } else {
                $error[] = "Sorry, there was an error uploading your file.";
            }
        }
    }
}

// Log errors
if (!empty($error)) {
    error_log("Error updating user data: " . implode(", ", $error));
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit user page</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form action="edit_user.php" method="post" enctype="multipart/form-data">
        <div class="bg-white p-8 rounded shadow-md w-96">
            <h2 class="text-2xl font-bold mb-6">Edit User</h2>
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
                <label class="block text-gray-700">Username</label>
                <input type="text" name="username" class="w-full border rounded px-3 py-2" value="<?php
                                                                                                    if (isset($_GET['id'])) {
                                                                                                        $id = $_GET['id'];
                                                                                                        $sql = "SELECT username, email, profile FROM users WHERE id=?";
                                                                                                        $stmt = $conn->prepare($sql);
                                                                                                        $stmt->bind_param("i", $id);
                                                                                                        $stmt->execute();
                                                                                                        $result = $stmt->get_result();
                                                                                                        $user = $result->fetch_assoc();
                                                                                                    }
                                                                                                    echo $user['username']; ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border rounded px-3 py-2" value="<?php
                                                                                                if (isset($_GET['id'])) {
                                                                                                    $id = $_GET['id'];
                                                                                                    $sql = "SELECT username, email, profile FROM users WHERE id=?";
                                                                                                    $stmt = $conn->prepare($sql);
                                                                                                    $stmt->bind_param("i", $id);
                                                                                                    $stmt->execute();
                                                                                                    $result = $stmt->get_result();
                                                                                                    $user = $result->fetch_assoc();
                                                                                                }
                                                                                                echo $user['email']; ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Profile Picture</label>
                <img src="<?php
                            if (isset($_GET['id'])) {
                                $id = $_GET['id'];
                                $sql = "SELECT username, email, profile FROM users WHERE id=?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                $user = $result->fetch_assoc();
                            }
                            echo $user['profile']; ?>" alt=" Profile Picture" class="flex-shrink-0 w-20 h-20 rounded-full display-flex float-left">
                <br>
                <input type="file" name="fileToUpload" class="w-3/4 h-15 border rounded px-3 py-2 display-flex float-right">
            </div>
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <button type="submit" name="submit" class="w-full bg-blue-500 text-white py-2 rounded">Upadte</button>

        </div>
    </form>
</body>

</html>